# Firstlight UI Showcase

The official NativePHP showcase and dogfooding app for [`firstlightui/nativephp`](https://github.com/firstlightui/nativephp).

[firstlightui.dev](https://firstlightui.dev) · [team@firstlightui.dev](mailto:team@firstlightui.dev)

## Setup

The app consumes the adjacent `../firstlight-ui` checkout through Composer's path repository. From this directory:

```bash
composer run setup
```

After changing NativePHP versions, run `composer update` and `php artisan native:install --force`.

## Local NativePHP development

### Identical-publication limitation

Firstlight's interactive controls must observe every PHP response, including a
publication whose element tree is byte-for-byte identical to the previous tree.
The bundled PHP Element Runtime can still suppress that identical publication
before iOS or Android can reconcile it. A rejected Segmented change can therefore
remain visible, while a server-authoritative Switch or Pill Group can remain
stuck waiting for a response.

NativePHP Mobile 4.2.0 ships Android equal-tree publication revisions
([#280](https://github.com/NativePHP/mobile-air/pull/280)). That is necessary
but not sufficient: the remaining cross-platform gap is tracked upstream as
[#365](https://github.com/NativePHP/mobile-air/issues/365).

Until an official NativePHP release includes a content-independent publication
acknowledgement in the bundled PHP runtime, Android pre-alpha work may still
use the guarded [temporary NativePHP publication
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
