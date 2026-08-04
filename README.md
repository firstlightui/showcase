# Firstlight UI Showcase

The official NativePHP showcase and dogfooding app for [`firstlightui/nativephp`](https://github.com/firstlightui/nativephp).

[firstlightui.dev](https://firstlightui.dev) · [team@firstlightui.dev](mailto:team@firstlightui.dev)

## Setup

The app consumes the adjacent `../firstlight-ui` checkout through Composer's path repository. From this directory:

```bash
composer run setup
```

## Local NativePHP development

Android pre-alpha work that depends on authoritative byte-identical tree
publications must use the guarded [temporary NativePHP publication
workaround](docs/nativephp-identical-publication-workaround.md).

## Documentation captures

The stable Segmented fixture is available at `/captures/segmented`. Verify its authored native tree before capturing:

```bash
php artisan test tests/Feature/SegmentedCaptureTest.php
```

The package repository's `bin/capture-doc-screenshots` workflow supplies the explicit iOS Simulator UDID and Android emulator serial. Do not substitute a physical device or choose a target implicitly.

## License

Firstlight UI Showcase is open-sourced software licensed under the [MIT license](LICENSE).
