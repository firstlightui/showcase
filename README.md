# Firstlight UI Showcase

The official NativePHP showcase and dogfooding app for [`firstlightui/nativephp`](https://github.com/firstlightui/nativephp).

[firstlightui.dev](https://firstlightui.dev) · [team@firstlightui.dev](mailto:team@firstlightui.dev)

## Setup

The app consumes the adjacent `../firstlight-ui` checkout through Composer's path repository. From this directory:

```bash
composer run setup
```

## Local NativePHP development

### Why NativePHP is temporarily pinned

The showcase currently resolves `nativephp/mobile` from a development
mobile-air branch. Firstlight's interactive controls must observe every PHP
response, including a publication whose element tree is byte-for-byte
identical to the previous tree. NativePHP 4.0.1 can suppress that identical
publication inside the bundled PHP Element Runtime before iOS or Android can
reconcile it. A rejected Segmented change can therefore remain visible, while
a server-authoritative Switch or Pill Group can remain stuck waiting for a
response.

The branch exposes an Android publication revision, but that platform change
cannot recover a response already suppressed by the bundled PHP binary. We are
waiting for an upstream NativePHP release whose PHP runtime provides a
content-independent publication acknowledgement before returning the showcase
to an official release. Until then, Android pre-alpha work uses the guarded
[temporary NativePHP publication
workaround](docs/nativephp-identical-publication-workaround.md).

The patched PHP binary is development-only and must not be included in a
release artifact.

## Documentation captures

The stable Segmented fixture is available at `/captures/segmented`. Verify its authored native tree before capturing:

```bash
php artisan test tests/Feature/SegmentedCaptureTest.php
```

The package repository's `bin/capture-doc-screenshots` workflow supplies the explicit iOS Simulator UDID and Android emulator serial. Do not substitute a physical device or choose a target implicitly.

## License

Firstlight UI Showcase is open-sourced software licensed under the [MIT license](LICENSE).
