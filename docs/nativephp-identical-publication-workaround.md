# Temporary NativePHP identical-publication workaround

## Status

This is a local Android/ARM64 development workaround for the NativePHP v4
Element Runtime. It is intended only for First Light pre-alpha development
while the shared runtime fix is being designed and released upstream.

Do not ship this patched runtime. Do not copy the patched `libphp.a` into a
release artifact. The committed script is deliberately strict so a changed or
unknown runtime fails instead of being modified silently.

The October 2026 Android PHP 8.5.11 bundle still suppresses identical frames,
but its `nphp_element.o` hash differs from the script's reviewed object. The
script will refuse that bundle. The workflow below documents the older proof;
do not bypass its hash or instruction guards to use it with a new runtime.

Tracking:

- [NativePHP/mobile-air#365](https://github.com/NativePHP/mobile-air/issues/365) — cross-platform C-runtime publication acknowledgement (remaining gap)
- [NativePHP/mobile-air#279](https://github.com/NativePHP/mobile-air/issues/279) — closed Android-only report
- [NativePHP/mobile-air#280](https://github.com/NativePHP/mobile-air/pull/280) — merged in NativePHP Mobile 4.1.0; Android `treePublicationId`

## The problem

An optimistic native control may send a value to PHP and then receive the same
authoritative tree that PHP published previously. That repeated publication is
still a response: the control must use it to discard its tentative value.

The shared C Element Runtime currently compares the encoded frame with its
shadow frame. When the node count, flat-buffer size, property-buffer size, and
both byte sequences are identical, `nphp_element_publish()` returns before it:

- advances the runtime tree version;
- signals the publication condition; or
- calls the platform `NativeElement_PostTreeUpdate()` callback.

The suppression happens before Kotlin or Swift receives the publication. The
Android `treePublicationId` shipped in NativePHP Mobile 4.1.0+ can represent
equal tree publications once they reach Kotlin, but it cannot observe a frame
that the shared runtime never delivers.

## What was proved locally

The First Light Showcase `Rejected queue` fixture was tested on an Android
ARM64 emulator.

Before the runtime patch, two rejected selections produced one
`postTreeUpdate()` followed by `BENCH_C shadow_skip` for the second identical
frame. After the patch:

- both responses reached Kotlin, as runtime tree versions 2 and 3;
- `postTreeUpdate()` ran twice;
- no `shadow_skip` occurred; and
- the optimistic `All` selection reconciled back to `Mine` after both attempts.

The focused publication-revision and segmented-selection reconciliation tests
passed, as did the Android JVM test and debug build tasks.

This proves the required behavior, not the final implementation. The proof
routes an identical frame through the existing full publication path. The
upstream implementation should retain content deduplication and emit a
separate publication/response acknowledgement for every successful PHP
publish.

## Required layers

The workaround is useful only when all three layers are present:

1. `nativephp/mobile` is resolved from an official **4.1.0+** release (the
   showcase currently requires `^4.6`), so Android exposes
   `NativeUIBridge.treePublicationId`.
2. The local generated ARM64 `libphp.a` is patched by the
   [repository script](../scripts/patch-nativephp-identical-publication.sh), so
   an identical PHP publication reaches Kotlin.
3. The optimistic control observes `treePublicationId` and reconciles from
   `NativeUIBridge.currentTree` on each revision, rather than observing only
   equality changes to `currentTree`.

For the First Light segmented control, its local selection state must also
actually assign the tentative selection before sending the event. Those are
normal First Light source changes; the binary workaround does not manufacture
optimistic behavior or patch plugin source.

## Local Android workflow

After resolving `nativephp/mobile` from Packagist, regenerate the Android
project and immediately apply the workaround:

```bash
composer update nativephp/mobile nativephp/mobile-ui firstlightui/nativephp
php artisan native:install android --force
./scripts/patch-nativephp-identical-publication.sh
php artisan native:run android --watch
```

Once the patched APK is installed, PHP and Blade changes can use the normal
watch loop without rebuilding the native shell:

```bash
php artisan native:watch android
```

`native:run android` is also safe after the generated project is patched. It
rebuilds and reinstalls the APK from `nativephp/android`, including its patched
static library.

Run the patch script again after any operation that replaces or regenerates
the native project, including:

- `native:install`;
- deleting or recreating `nativephp/android`;
- changing the embedded PHP version; or
- upgrading the NativePHP runtime bundle.

The script is idempotent. It exits successfully when the reviewed patch is
already present. It refuses to run when:

- the generated bridge does not contain `treePublicationId`;
- the runtime object hash is not the reviewed proof-of-concept hash;
- the expected instruction or ELF section layout has changed; or
- the configured Android NDK tools cannot be found.

On its first successful run it preserves the generated original alongside the
archive as:

```text
libphp.a.before-identical-publication-workaround
```

Both the patched archive and backup are generated development artifacts. A
fresh `native:install android` is the authoritative way to restore the upstream
runtime.

## Limits and removal

- Only Android ARM64 has been verified.
- iOS still requires the upstream acknowledgement path and its own tests.
- The patched path performs a full publication for identical frames, so it
  intentionally gives up the current whole-frame optimization.
- Rapid queued interactions ultimately need an acknowledged event sequence,
  not merely an anonymous increment, if responses may be coalesced.

Delete the script and this workaround document after the released NativePHP
runtime provides a content-independent publication acknowledgement tracked in
[#365](https://github.com/NativePHP/mobile-air/issues/365).
