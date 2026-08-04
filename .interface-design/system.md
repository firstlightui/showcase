# Firstlight Showcase Interface System

## Intent

The showcase is a calm, native developer reference for people checking how published Firstlight controls look and behave on a real platform. It should feel like a small component catalogue, not a marketing site or a dashboard.

Use platform-native typography, navigation, lists, controls, and accessibility semantics. Firstlight's dawn-and-ink theme provides identity while iOS and Material conventions provide familiarity.

## Structure

- `/` is the catalogue and the default `NATIVEPHP_START_URL`.
- The catalogue uses a native grouped list. Each published component row shows its human name, exact Blade tag, a concise capability summary, and a trailing navigation affordance.
- Interactive component pages extend `ShowcaseScreen`, use `ShowcaseLayout`, and receive an inline native title plus a native back button.
- Capture pages remain focused fixtures. They do not inherit interactive back navigation or the floating appearance control.
- Navigation and shared overlays belong in `ShowcaseLayout`; individual demos do not recreate them.

## Appearance

- The floating appearance capsule is the one permitted elevated surface. Use a subtle shadow and native toggle; keep all other depth native to the platform list and chrome.
- The choice previews Firstlight's token-driven light or dark palette without changing the device's operating-system appearance.
- Persist the preview across interactive routes. Exclude deterministic capture routes.
- Use a 4-point spacing base. Prefer native sizing and symmetrical spacing; keep the overlay 12 points from the bottom edge.

## Adding a Published Component

1. Preserve concurrent work in the shared showcase repository.
2. Add an interactive `ShowcaseScreen` and a separate `/captures/<slug>` fixture.
3. Register both routes with `ShowcaseLayout`.
4. Add the component to `ShowcaseHome` using its exact `<firstlight:...>` tag and a one-line capability summary.
5. Keep the application start route at `/` and run focused plus full PHP consumer tests.
6. Do not list, launch, boot, switch, reset, or stop a simulator or emulator without the user's explicit permission for that target.

## Interaction and Accessibility

- Prefer native navigation actions over custom in-content links.
- Give controls action-oriented accessibility labels and useful hints.
- Keep touch targets, text scaling, contrast, and screen-reader behaviour platform-native.
- Appearance changes should be immediate and quiet; do not add decorative animation.
