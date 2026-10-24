---
version: 1
slug: "app-nativecomponents-galleryscreen-php"
primary_target: "app/NativeComponents/GalleryScreen.php"
related_targets: ["app/NativeComponents/ShowcaseHome.php","app/NativeComponents/RecipesScreen.php","app/NativeComponents/Layouts/ShowcaseLayout.php"]
---

# Showcase shell

Scope: the showcase app shell (tab chrome, Gallery, Recipes, Components). Mode: Experience. Demo pages and `/captures/*` fixtures are out of scope.

Audience and job: a Laravel developer holding an iPhone or Android phone who wants proof that Blade drives genuine native UI; a tester who needs every component demo.

## Direction contract

THESIS: The showcase is an app, not a test index. Its first tab runs a believable product built only from Firstlight tags with live PHP state. This replaces the category default of an alphabetical component list with tag overlines.

OWN-WORLD: The platform's own grammar on each OS: SwiftUI TabView with Liquid Glass and inset grouped lists on iOS; Material 3 NavigationBar, TopAppBar and list items on Android. Firstlight dawn-and-ink tokens supply the tint (Dawn 700 or 500 primary over slate ink). Category icons use semantic tones, SF Symbols on iOS and Material Symbols on Android. The app follows system appearance.

STORY: The visitor books a dawn surf lesson. Date, session, riders and board hire update a live total; Book sends native feedback with Undo. They then triage an inbox with swipes and the context menu, open the four recipe apps, and browse the catalogue.

FIRST VIEWPORT: The Gallery tab shows the Dawn Patrol booking as an inset grouped form: next session status, Metric availability, Date Picker and Segmented session, Stepper riders, a hire Switch, a live total Metric and a full-width primary Book button. The three-tab bar sits at the bottom.

FORM: native platform grammar (brief-pinned; no concept roll because the brief pins the world and structure).

FINISH: unreviewed and undocumented is unfinished; this build ends with the finish review, the verdict, DESIGN.md, and every shipping raster carrying its provenance.

## Unresolved

- NativePHP Mobile 4.6 tab chrome renders tab-root titles inline on iOS and as a small TopAppBar on Android. Large titles inside tabs need an upstream change.
