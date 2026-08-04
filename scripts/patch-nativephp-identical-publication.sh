#!/usr/bin/env bash

set -euo pipefail

# Local Android/ARM64 proof-of-concept only. This rewrites one reviewed branch
# instruction in the shipped Element Runtime so byte-identical frames reach the
# existing native publication callback. Remove it when the upstream runtime
# provides a content-independent publication acknowledgement.
readonly ORIGINAL_OBJECT_SHA256="d9922e5f358878348966cc294d476ea2ebd7313e7661be61ba63a5fb66adc356"
readonly PATCHED_OBJECT_SHA256="fdb3c9a0577a19f263755ffc8a130bee72318acd2d35d1c43604916e54c73700"
readonly PATCH_OFFSET=1216
readonly ORIGINAL_BYTES="200751f9"
readonly PATCHED_BYTES="4fffff17"

script_dir="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
project_root="$(cd "${script_dir}/.." && pwd)"
android_root="${project_root}/nativephp/android"
archive="${android_root}/app/src/main/staticLibs/arm64-v8a/libphp.a"
bridge="${android_root}/app/src/main/java/com/nativephp/mobile/ui/nativerender/NativeUIBridge.kt"
local_properties="${android_root}/local.properties"
gradle_build="${android_root}/app/build.gradle.kts"

fail() {
    printf 'NativePHP publication workaround: %s\n' "$*" >&2
    exit 1
}

sha256_file() {
    if command -v shasum >/dev/null 2>&1; then
        shasum -a 256 "$1" | awk '{print $1}'
        return
    fi

    if command -v sha256sum >/dev/null 2>&1; then
        sha256sum "$1" | awk '{print $1}'
        return
    fi

    fail 'neither shasum nor sha256sum is available'
}

read_bytes() {
    od -An -tx1 -j "${PATCH_OFFSET}" -N 4 "$1" | tr -d ' \n'
}

find_llvm_tools() {
    local sdk_dir="${ANDROID_SDK_ROOT:-${ANDROID_HOME:-}}"
    local ndk_version=''
    local prebuilt_root=''

    if [[ -z "${sdk_dir}" && -f "${local_properties}" ]]; then
        sdk_dir="$(sed -n 's/^sdk\.dir=//p' "${local_properties}" | tail -n 1)"
    fi

    if [[ -z "${sdk_dir}" && -d "${HOME}/Library/Android/sdk" ]]; then
        sdk_dir="${HOME}/Library/Android/sdk"
    elif [[ -z "${sdk_dir}" && -d "${HOME}/Android/Sdk" ]]; then
        sdk_dir="${HOME}/Android/Sdk"
    fi

    if [[ -n "${ANDROID_NDK_ROOT:-}" ]]; then
        prebuilt_root="${ANDROID_NDK_ROOT}/toolchains/llvm/prebuilt"
    elif [[ -n "${ANDROID_NDK_HOME:-}" ]]; then
        prebuilt_root="${ANDROID_NDK_HOME}/toolchains/llvm/prebuilt"
    elif [[ -n "${sdk_dir}" && -f "${gradle_build}" ]]; then
        ndk_version="$(sed -nE 's/.*ndkVersion[[:space:]]*=[[:space:]]*"([^"]+)".*/\1/p' "${gradle_build}" | head -n 1)"
        if [[ -n "${ndk_version}" ]]; then
            prebuilt_root="${sdk_dir}/ndk/${ndk_version}/toolchains/llvm/prebuilt"
        fi
    fi

    if [[ -n "${prebuilt_root}" && -d "${prebuilt_root}" ]]; then
        llvm_ar="$(find -L "${prebuilt_root}" -type f -path '*/bin/llvm-ar' -perm -u+x | sort | tail -n 1)"
        llvm_readelf="$(find -L "${prebuilt_root}" -type f -path '*/bin/llvm-readelf' -perm -u+x | sort | tail -n 1)"
    fi

    if [[ -z "${llvm_ar:-}" ]] && command -v llvm-ar >/dev/null 2>&1; then
        llvm_ar="$(command -v llvm-ar)"
    fi

    if [[ -z "${llvm_readelf:-}" ]] && command -v llvm-readelf >/dev/null 2>&1; then
        llvm_readelf="$(command -v llvm-readelf)"
    fi

    [[ -n "${llvm_ar:-}" && -x "${llvm_ar}" ]] || fail 'could not find llvm-ar in the configured Android NDK'
    [[ -n "${llvm_readelf:-}" && -x "${llvm_readelf}" ]] || fail 'could not find llvm-readelf in the configured Android NDK'
}

[[ -f "${archive}" ]] || fail "missing ${archive}; run native:install android first"
[[ -f "${bridge}" ]] || fail "missing ${bridge}; run native:install android first"
grep -q 'treePublicationId' "${bridge}" || fail 'the generated Android bridge does not contain treePublicationId; install from the mobile-air publication-revision branch first'

find_llvm_tools

tmp_dir="$(mktemp -d "${TMPDIR:-/tmp}/nativephp-publication-workaround.XXXXXX")"
replacement="${archive}.publication-workaround.tmp.$$"

cleanup() {
    rm -rf -- "${tmp_dir}"
    rm -f -- "${replacement}"
}
trap cleanup EXIT

working_archive="${tmp_dir}/libphp.a"
object="${tmp_dir}/nphp_element.o"
cp -p "${archive}" "${working_archive}"

(
    cd "${tmp_dir}"
    "${llvm_ar}" x "${working_archive}" nphp_element.o
)

[[ -f "${object}" ]] || fail 'nphp_element.o was not found in libphp.a'

object_sha256="$(sha256_file "${object}")"
object_bytes="$(read_bytes "${object}")"

if [[ "${object_sha256}" == "${PATCHED_OBJECT_SHA256}" && "${object_bytes}" == "${PATCHED_BYTES}" ]]; then
    printf 'NativePHP publication workaround is already applied.\n'
    exit 0
fi

[[ "${object_sha256}" == "${ORIGINAL_OBJECT_SHA256}" ]] || fail "unsupported nphp_element.o hash ${object_sha256}; the runtime changed, so this workaround must be reviewed"
[[ "${object_bytes}" == "${ORIGINAL_BYTES}" ]] || fail "unexpected instruction bytes ${object_bytes} at offset ${PATCH_OFFSET}"

section_line="$("${llvm_readelf}" -SW "${object}" | grep -F '.text.nphp_element_publish' | head -n 1)"
[[ "${section_line}" == *'PROGBITS 0000000000000000 000080 000544'* ]] || fail 'nphp_element_publish has an unexpected ELF section layout'

printf '\x4f\xff\xff\x17' | dd of="${object}" bs=1 seek="${PATCH_OFFSET}" conv=notrunc 2>/dev/null

[[ "$(sha256_file "${object}")" == "${PATCHED_OBJECT_SHA256}" ]] || fail 'patched object hash did not match the reviewed proof-of-concept'
[[ "$(read_bytes "${object}")" == "${PATCHED_BYTES}" ]] || fail 'patched branch instruction could not be verified'

(
    cd "${tmp_dir}"
    "${llvm_ar}" r "${working_archive}" nphp_element.o
    "${llvm_ar}" s "${working_archive}"
    rm nphp_element.o
    "${llvm_ar}" x "${working_archive}" nphp_element.o
)

[[ "$(sha256_file "${object}")" == "${PATCHED_OBJECT_SHA256}" ]] || fail 'patched object could not be verified after archive replacement'

backup="${archive}.before-identical-publication-workaround"
if [[ ! -e "${backup}" ]]; then
    cp -p "${archive}" "${backup}"
fi

cp -p "${working_archive}" "${replacement}"
mv -f "${replacement}" "${archive}"

printf '%s\n' \
    'Applied the temporary NativePHP identical-publication workaround.' \
    "Patched: ${archive}" \
    "Backup:  ${backup}" \
    'The next Android native:run build will include the patched runtime.' \
    'Any native:install/regeneration restores the upstream runtime; rerun this script afterward.'
