# Product

<!-- impeccable:product-schema 1 -->

Recorded 2026-10-24 from the maintainer's redesign brief and the repository.
No interview took place; facts inferred from the brief or code are marked
_(inferred)_.

## Platform

adaptive

## Users

- Laravel developers deciding whether Firstlight's Blade tags really produce
  native iOS and Android UI. They hold the app on a phone, often one of each
  platform side by side. _(inferred from the brief: "make someone say 'that's
  native, from Blade'")_
- The Firstlight team and testers, who dogfood every published component and
  documented state on simulators, emulators and physical devices before a
  release (Constitution Article IX.6).

## Product Purpose

The showcase is the official NativePHP app for `firstlightui/nativephp`. It
proves real Composer installation and plugin registration, shows every
published control in a working screen, and runs the documented app recipes
against SQLite. Success: a first-time viewer understands within one screen
that genuine SwiftUI and Jetpack Compose controls are being driven by PHP
state, and a tester can still reach every demo and its on-device checks.

## Positioning

Every control on screen is a published `<firstlight:...>` tag or Mobile UI
chrome rendered by the platform toolkit. Nothing is drawn by hand or in a
WebView.

## Operating Context

- Built and run with `php artisan native:run` on iOS simulators, Android
  emulators and physical devices.
- `/captures/*` routes are deterministic documentation fixtures. The package's
  `bin/capture-doc-screenshots` tooling switches system appearance with
  `simctl ui appearance` and `adb shell cmd uimode night` and must keep
  working.
- Recipe screens mirror the package's how-to guides verbatim.
- The Docs tab bundles the installed Firstlight revision's full reference for
  offline search and reading, with selectable code, bundled screenshots,
  internal article links and links to live examples. External references need
  a connection.

## Capabilities and Constraints

- Colour comes only from `config/native-ui.php` theme tokens; the app follows
  the system light or dark appearance.
- Navigation and bars are Mobile UI native chrome (SwiftUI `TabView` /
  `NavigationStack`, Material 3 `NavigationBar` / `TopAppBar`). No custom-drawn
  controls, charts, carousels or bars.
- New `<firstlight:...>` tags are only for semantic controls with equal iOS and
  Android contracts (package AGENTS.md catalogue rule).
- Demo data is sample data. The app charges, sends and stores nothing real.

## Brand Commitments

- Firstlight dawn-and-ink identity: Dawn 700 primary in light, Dawn 500 in
  dark, slate ink neutrals (see `config/native-ui.php`).
- Sunrise app icon and splash artwork in `public/`.

## Product Principles

1. Prove, don't claim: show controls doing real work with live PHP state.
2. Platform first: native typography, navigation, materials and gestures on
   each OS; behavioural parity, not pixel parity.
3. Testers keep a path to every component and its checks.
4. Accessibility is correctness: VoiceOver, TalkBack, Dynamic Type and font
   scale must work on every screen.

## Accessibility & Inclusion

Every screen must pass `assertAccessible()`, keep 44 pt / 48 dp targets, and
read correctly at the largest Dynamic Type and Android font scale.
