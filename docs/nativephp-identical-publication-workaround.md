# Firstlight response revisions and official runtime migration

## Status

The temporary Android binary workaround is retired. Do not patch `libphp.a`,
change runtime object hashes, or modify vendor/runtime code. Firstlight uses a
PHP screen response contract on official NativePHP Mobile `^4.7.0` and Mobile UI
`^0.9.0` binaries instead. The old document URL is retained for existing links.

## Supported screen contract

`ShowcaseScreen` extends `FirstlightUI\NativeComponent`, not the upstream
EDGE base. Consumer screens should do the same:

```php
use FirstlightUI\NativeComponent;

class SettingsScreen extends NativeComponent
{
    // Normal Blade rendering is automatic.
}
```

Firstlight stamps the final chrome-wrapped root with the integer prop
`firstlight_response_revision`. A private process-wide counter increases across
screen instances within the PHP process, making an otherwise unchanged full
response distinct. Do not bind, persist, or author this internal prop.

- A global Laravel `View` returned from `render()`, `$this->view()`, and inferred
  views are automatic.
- For a hand-built Element root, return `$this->response($element)` from
  `render()`. This also adds normal screen chrome.
- Nested children are emitted untouched; partials are unstamped.
- `renderStreaming()` is final and returns `false`; streaming cannot be opted
  into because it bypasses the revision-bearing response.
- Existing traits remain usable separately, but do not provide this publication
  contract when added to the upstream base.

## Migrate back to official binaries

Stop any watch/build loop first. Remove any local automation that invokes the
retired patch script; do not reapply it or update its hash guards. From the
showcase directory, restore the generated projects from the resolved official
NativePHP release:

```bash
php artisan native:install --force
```

Then perform a clean native rebuild through the normal NativePHP build/run
workflow for each platform you use. Do not reuse an APK or native build output
linked against the patched archive. `native:install --force` restores the
official bundled binaries; a clean rebuild ensures they are linked into the
application. Do not uninstall the app, clear its data, or delete application
storage as part of this migration. No application-data deletion is required.

## Limits and qualification

The revision is not a per-event acknowledgement. It does not prevent response
coalescing or serialize simultaneous proposals, and it does not guarantee a
separately observable publication for every event. It applies to complete
nonstreaming responses, not nested children or partials.

The permanent implementation passed 14 nonstreaming phases on each platform
on October 19, 2026, with Mobile 4.6.0, Mobile UI 0.8.0, and official PHP 8.5.11.
Each phase delivered three proposals using a normal Blade screen with no
app-authored revision props: repeated Switch/Pill and Segmented rejection,
validation, accepted Switch changes, 500 ms rejection, and rapid same-control
bursts. Rapid bursts intentionally collapse a second tap while pending; this
does not establish one response per physical tap. The earlier spike did not
reproduce a baseline Segmented failure, so this is not evidence of a Segmented
fix. Firstlight remains pre-alpha pending physical-device, soak/performance,
and wider application qualification, including simultaneous controls.

## Historical context

The shared C Element Runtime suppresses byte-identical frames before either
platform reader receives them. Android `treePublicationId`, shipped in Mobile
4.1.0, only helps once a tree reaches Kotlin. The old Android ARM64 experiment
forced repeated identical frames through full publication: two rejected
Segmented selections then both reconciled from `All` to `Mine`, rather than
the second response being shadow-skipped. That established the missing delivery
path, not a supported binary distribution or final acknowledgement design.

The October 2026 PHP 8.5.11 bundles still contain the upstream suppression, and
the old patch's reviewed object hash no longer matched. The patch is retired
rather than extended. The response revision avoids byte-identical full frames
without changing the runtime; Firstlight no longer plans to wait for upstream.

Tracking retained for context:

- [#365](https://github.com/NativePHP/mobile-air/issues/365): remaining cross-platform identical-frame bug.
- [#279](https://github.com/NativePHP/mobile-air/issues/279): closed Android-only report.
- [#280](https://github.com/NativePHP/mobile-air/pull/280): Android `treePublicationId`, shipped in Mobile 4.1.0.
