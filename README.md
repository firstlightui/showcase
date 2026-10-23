# Firstlight UI Showcase

The official NativePHP showcase and dogfooding app for [`firstlightui/nativephp`](https://github.com/firstlightui/nativephp).

[firstlightui.dev](https://firstlightui.dev) · [team@firstlightui.dev](mailto:team@firstlightui.dev)

## Setup

The app consumes the adjacent `../nativephp` checkout through Composer's path repository. From this directory:

```bash
composer run setup
```

After changing NativePHP versions, run `composer update` and `php artisan native:install --force`.

## App artwork

The sunrise identity follows the showcase's dawn-and-ink palette. NativePHP 4.6
automatically consumes these files when preparing a native build:

- [`public/icon.png`](public/icon.png) — opaque 1024 × 1024 app icon; platform masks supply the rounded corners.
- [`public/splash.png`](public/splash.png) — 887 × 1774 light launch artwork.
- [`public/splash-dark.png`](public/splash-dark.png) — matching dark launch artwork.

The splash pair keeps the emblem and wordmark centered, with generous margins
for different screen proportions. iOS selects its appearance variant and
Android generates density-specific light and night resources. Rebuild the
native app to pick up artwork changes; a web refresh does not update them.

Artwork was generated with the built-in image generation tool. The final
[prompts](resources/branding/prompts.json) are saved for future revisions.

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
1.0.0 is a release candidate pending physical-device, soak/performance, and
wider app qualification. The permanent implementation passed 14 emulator/simulator
phases per platform on unpatched PHP 8.5.11; this is not production readiness.

## Live device testing

- **iOS:** set `NATIVEPHP_DEVELOPMENT_TEAM` in `.env` to your Apple Team ID so
  builds sign for a physical iPhone. The Media screen's Camera option uses the
  `NSCameraUsageDescription` in `config/nativephp.php`.
- **Android:** the showcase targets API 29, Firstlight's floor, rather than
  NativePHP's default of 33, so older supported devices install it too.
- Each screen starts with its on-device checks. Media and Transient Feedback
  (screen-local Undo) exercise paths that simulators and emulators cannot
  fully cover: the real camera, system pickers and native event delivery.

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
