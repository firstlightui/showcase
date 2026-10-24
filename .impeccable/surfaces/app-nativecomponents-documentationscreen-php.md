---
version: 1
slug: "app-nativecomponents-documentationscreen-php"
primary_target: "app/NativeComponents/DocumentationScreen.php"
related_targets: ["app/NativeComponents/DocumentationArticleScreen.php","resources/views/native/documentation.blade.php","resources/views/native/documentation-article.blade.php","app/NativeComponents/Layouts/ShowcaseLayout.php"]
---

# Documentation

Scope: offline documentation in the existing native Showcase app. Mode: Read.

## Direction contract

THESIS: A developer can find an answer, read the actual API contract, copy an example and try its native control without leaving the app.

OWN-WORLD: Inherit Firstlight's semantic dawn tint, system typography, native grouped lists, navigation stack and tab bar. Code uses system monospace and native text selection. No new visual identity.

STORY: Search the full bundled library or browse its guide groups; open an article; choose a section; follow an internal reference or try its live example. Content is pinned to the installed Firstlight revision and works offline.

FIRST VIEWPORT: A Docs tab with a labelled search field, a short offline-library explanation, and grouped article rows. The reader opens with its title, description, section picker, optional live-example action, and readable body. Long code wraps and remains selectable.

FORM: Native reference library extending the existing app shell; precise confirmed scope, no concept roll.

FINISH: Reviewed and documented as an ordinary extension of the incumbent native system. Finish-review disposition: ship for the captured phone surfaces below.

## Completed evidence — 2026-10-25

Source head: `517116a8d7ad9c2edb333548d5bd2999dd14d9ba`. The bundled catalogue contains 55 articles from Firstlight `v1.0.0-RC7`, revision `d61c7e2300f20fcfb110ad68850fa3427028dc85`.

- iOS captures: `.impeccable/review/phone-ios.png` (index), `phone-ios-code.png` (complete code example), `phone-ios-images.png` (bundled screenshot), and `phone-ios-dark-large.png` (top of the scrolling reader in dark appearance with larger text).
- Android captures: `.impeccable/review/phone-android.png` (light index) and `phone-android-dark-large.png` (dark index with larger text). Android reader visuals were unavailable and are not covered by the visual ship disposition.
- Finish review found no material fixes in those six captures. Source review verified internal links, section anchors and live-example destinations; native copy-menu behaviour was verified by long press. These checks do not establish full VoiceOver or TalkBack coverage.

The implementation retains `config/native-ui.php` semantic colours, system type, native search and grouped lists, the existing navigation stack and tab bar. The reader adds selectable system-monospace code on the existing surface colour, native section selection and secondary/ghost actions. No new visual world or reusable token system was introduced.

Pre-existing context drift: `DESIGN.md` is absent, and the shell brief still describes three tabs. Those files were not created or repaired by this extension; the shipped shell has Gallery, Recipes, Components and Docs.
