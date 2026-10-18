# Firstlight UI Showcase

The official NativePHP showcase and dogfooding app for [`firstlightui/nativephp`](https://github.com/firstlightui/nativephp).

[firstlightui.dev](https://firstlightui.dev) · [team@firstlightui.dev](mailto:team@firstlightui.dev)

## Setup

The app consumes the adjacent `../nativephp` checkout through Composer's path repository. From this directory:

```bash
composer run setup
```

After changing NativePHP versions, run `composer update` and `php artisan native:install --force`.

## Local NativePHP development

### Response-revision contract

The supported screen base is `FirstlightUI\NativeComponent`; `ShowcaseScreen`
extends it. On Mobile `^4.6` / Mobile UI `^0.8`, it adds a process-wide
increasing revision to each complete nonstreaming response's final chrome root.
Blade paths are automatic; programmatic Element roots must return
`$this->response($element)`. Nested children are untouched, partials are
unstamped, and streaming is final-disabled.

Use official, unpatched binaries. The old Android binary patch is retired, not
updated for new runtime hashes. See the [contract and migration guide](docs/nativephp-identical-publication-workaround.md)
to restore official binaries without deleting application data.

Upstream [#365](https://github.com/NativePHP/mobile-air/issues/365) remains, but
Firstlight no longer waits for it. A revision is not a per-event acknowledgement:
it does not prevent coalescing or serialize simultaneous proposals. Firstlight
remains pre-alpha pending physical-device, soak/performance, and wider app
qualification. The permanent implementation passed 14 emulator/simulator
phases per platform on unpatched PHP 8.5.11; this is not production readiness.

## Documentation captures

The stable Segmented fixture is available at `/captures/segmented`. The Alert
Dialog capture fixture is `/captures/alert-dialog`. Verify its authored native
tree before capturing:

```bash
php artisan test tests/Feature/SegmentedCaptureTest.php
```

The package repository's `bin/capture-doc-screenshots` workflow supplies the explicit iOS Simulator UDID and Android emulator serial. Do not substitute a physical device or choose a target implicitly.

## License

Firstlight UI Showcase is open-sourced software licensed under the [MIT license](LICENSE).
