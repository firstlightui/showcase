# Firstlight Showcase Interface System

## Intent

The showcase is an app that proves Firstlight's Blade tags render genuine SwiftUI and Material 3 UI. The first thing a visitor sees is a working product built from Firstlight controls, not a list of tags. Testers still reach every published component and its on-device checks.

Use platform-native typography, navigation, lists, controls, materials and accessibility semantics. Firstlight's dawn-and-ink theme tokens provide identity; iOS and Material conventions provide familiarity.

## Structure

`ShowcaseLayout` returns a native tab bar (`usesNativeChrome()`): SwiftUI `TabView` with Liquid Glass on iOS 26+, Material 3 `NavigationBar` on Android. Tabs own screens by URL prefix and switch with `replace`.

| Tab | Root | Screens it pushes |
| --- | --- | --- |
| Gallery | `/` (`GalleryScreen`, the start URL) | `/gallery/inbox` |
| Recipes | `/recipes` | Recipe routes with their documented `AppLayout` |
| Components | `/components` (`ShowcaseHome`) | `/components/<slug>` demos |

- Gallery owns `/`, so it stays the first tab: native chrome gives URIs no tab prefixes to the first tab.
- Tab roots set no back button. Pushed screens get an inline title and the native back button; iOS hides the tab bar on pushed levels.
- NativePHP Mobile 4.6 renders tab-root titles inline (iOS) and in a small top app bar (Android). The layout still asks for `large`, so a later NativePHP release can show large titles.
- Capture fixtures (`/captures/*`) are not `ShowcaseScreen`s: they get a plain inline bar, no tabs and no back button.

## Gallery

Vignettes are believable sample apps built only from published Firstlight tags with live PHP state:

- **Dawn Patrol** (Gallery root): an inset grouped booking form with Metric, Status Label, Date Picker, Segmented, Stepper, Switch and Button. Booking sends Transient Feedback whose Undo restores the boards.
- **Inbox** (`/gallery/inbox`): List Item rows with monograms, swipe actions, the context menu, a Confirmation Dialog before delete, and archive with Undo.

Footers say the data is sample data. Status that an icon conveys is also spoken through the row's `a11y-label`.

## Components

`ShowcaseHome` is the catalogue: one grouped `firstlight:list` section per category, sorted by name. Each row has a leading icon (`leading-icon` with `-ios` SF Symbol and `-android` Material Icons overrides), a section `leading-icon-tone`, a short human description and a chevron. Exact Blade tags are kept in the `$components` array and are searchable, but not shown. A Search Field filters names, tags, descriptions and sections; an empty result shows a Callout with Clear search.

## Appearance

- The app follows the system appearance. Never load one palette into both theme slots, and never set bar colours or `dark`.
- Colour comes only from `config/native-ui.php` tokens and semantic tones.
- Documentation screenshot tooling switches appearance with `simctl ui appearance` and `adb shell cmd uimode night`.
- Use a 4-point spacing base and native list insets.
- `native:list` rows have zero insets and a clear background on iOS, so form controls placed straight in a section sit flush and clip their first glyph. Wrap a section's controls in one `native:column` with `px-4 py-4 gap-4 bg-theme-surface rounded-2xl` (the Gallery cards).
- A screen with fixed content above a `firstlight:list` (Components search, Inbox filters) paints `bg-theme-background` on both the screen column and the list, so the header and list share one background in both appearances.

## Adding a Published Component

1. Preserve concurrent work in the shared showcase repository.
2. Add an interactive `ShowcaseScreen` at `/components/<slug>` and a separate `/captures/<slug>` fixture, both with `ShowcaseLayout`.
3. Add the component to `ShowcaseHome::$components` with its exact `<firstlight:...>` tag, a short description, a section and an icon triple.
4. Keep the application start route at `/` and run focused plus full PHP consumer tests.
5. Do not list, launch, boot, switch, reset, or stop a simulator or emulator without the user's explicit permission for that target.

## Interaction and Accessibility

- Prefer native navigation, swipe actions, context menus and system sheets over in-content links or custom controls.
- Give controls action-oriented accessibility labels and useful hints.
- Keep touch targets, text scaling, contrast, and screen-reader behaviour platform-native. Every screen passes `assertAccessible()`.
