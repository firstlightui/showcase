<?php

use Native\Mobile\Testing\Native;

/** @return list<array<string, mixed>> */
function showcaseNodesOfType(array $tree, string $type): array
{
    $nodes = ($tree['type'] ?? null) === $type ? [$tree] : [];

    foreach ($tree['children'] ?? [] as $child) {
        $nodes = [...$nodes, ...showcaseNodesOfType($child, $type)];
    }

    return $nodes;
}

it('publishes a native front-page menu for every released component', function () {
    $screen = Native::visit('/');
    $items = showcaseNodesOfType($screen->tree(), 'list_item');

    expect($screen->tree()['props'])->toMatchArray([
        'title' => 'Firstlight UI',
        'back' => false,
    ])->and(array_column(array_column($items, 'props'), 'headline'))->toEqualCanonicalizing([
        'Activity Indicator',
        'Button',
        'Callout',
        'Transient Feedback',
        'Badge',
        'Checkbox',
        'Media',
        'Choice Group',
        'Alert Dialog',
        'Confirmation Dialog',
        'Modal',
        'Bottom Sheet',
        'Date Picker',
        'Time Picker',
        'Icon Button',
        'List Item',
        'Collection List',
        'Pill Group',
        'Progress',
        'Segmented',
        'Search Field',
        'Select',
        'Slider',
        'Stepper',
        'Status Label',
        'Metric',
        'Switch',
        'Tabs',
        'Text Field',
        'Text Area',
        'Code Editor',
        'Validation',
    ])->and(array_column(array_column($items, 'props'), 'overline'))->toEqualCanonicalizing([
        '<firstlight:activity-indicator>',
        '<firstlight:button>',
        '<firstlight:callout>',
        'Transient Feedback',
        '<firstlight:badge>',
        '<firstlight:checkbox>',
        '<firstlight:media>',
        '<firstlight:choice-group>',
        '<firstlight:alert-dialog>',
        '<firstlight:confirmation-dialog>',
        '<firstlight:modal>',
        '<firstlight:bottom-sheet>',
        '<firstlight:date-picker>',
        '<firstlight:time-picker>',
        '<firstlight:icon-button>',
        '<firstlight:list-item>',
        '<firstlight:list> · <firstlight:list-section>',
        '<firstlight:pill-group>',
        '<firstlight:progress>',
        '<firstlight:segmented>',
        '<firstlight:search-field>',
        '<firstlight:select>',
        '<firstlight:slider>',
        '<firstlight:stepper>',
        '<firstlight:status-label>',
        '<firstlight:metric>',
        '<firstlight:switch>',
        '<firstlight:tabs>',
        '<firstlight:text-field>',
        '<firstlight:text-area>',
        '<firstlight:code-editor>',
        'ValidatesFields · SubmitsForms',
    ])->and(array_column(array_column($items, 'props'), 'supporting'))->toEqualCanonicalizing([
        'Indeterminate native activity with semantic sizes',
        'Labelled actions, variants, sizes, icons, and states',
        'Persistent semantic messages with an optional action',
        'Queued app-level outcomes that survive native navigation',
        'Compact display-only counts and semantic markers',
        'Server-authoritative Boolean form and checklist state',
        'One image or document with Storage and optional crop',
        'Visible single-radio and multiple-checkbox choices',
        'Native one-button acknowledgement',
        'Native confirmation, cancellation, and destructive roles',
        'Full-screen overlay for authored content',
        'Bottom panel for authored content',
        'Nullable calendar dates, bounds, and native confirmation',
        'Nullable wall-clock times and native confirmation',
        'Compact accessible actions with native icon controls',
        'Tappable application rows with identity and affordances',
        'Grouped sections, refresh, pagination and authorized sample deletion',
        'Compact single- and multiple-selection options',
        'Determinate and indeterminate work state',
        'Server-authoritative single selection',
        'Native query entry, clear, and submission behaviour',
        'Stable single selection with automatic search',
        'Strict stepped numeric ranges with native gestures',
        'Exact bounded increments with server authority',
        'Compact semantic status metadata',
        'One accessible measurement with tabular figures',
        'Server-authoritative boolean settings',
        'Server-authoritative sections with counts',
        'Native text entry, validation, and affordances',
        'Native multiline editing, validation, and synchronisation',
        'Monospaced JSON, YAML, and env editing with a key row and live-log follow',
        'Laravel validation, accepted submissions and success feedback',
    ]);

    foreach ($items as $item) {
        expect($item['on_press'] ?? null)->toBeInt();
    }

    $screen->assertAccessible();
});

it('navigates each catalogue row to its component demo', function (string $label, string $path) {
    Native::visit('/')
        ->tap($label)
        ->assertNavigatedTo($path);

    Native::visit($path)->assertSee('On-device checks')->assertAccessible();
})->with([
    ['Activity Indicator', '/activity-indicator'],
    ['Button', '/button'],
    ['Callout', '/callout'],
    ['Transient Feedback', '/transient-feedback'],
    ['Badge', '/badge'],
    ['Checkbox', '/checkbox'],
    ['Media', '/media'],
    ['Choice Group', '/choice-group'],
    ['Alert Dialog', '/alert-dialog'],
    ['Confirmation Dialog', '/confirmation-dialog'],
    ['Modal', '/modal'],
    ['Bottom Sheet', '/bottom-sheet'],
    ['Date Picker', '/date-picker'],
    ['Time Picker', '/time-picker'],
    ['Icon Button', '/icon-button'],
    ['List Item', '/list-item'],
    ['Collection List', '/list'],
    ['Pill Group', '/pill-group'],
    ['Progress', '/progress'],
    ['Segmented', '/segmented'],
    ['Search Field', '/search-field'],
    ['Select', '/select'],
    ['Slider', '/slider'],
    ['Stepper', '/stepper'],
    ['Status Label', '/status-label'],
    ['Metric', '/metric'],
    ['Switch', '/switch'],
    ['Tabs', '/tabs'],
    ['Text Field', '/text-field'],
    ['Text Area', '/text-area'],
    ['Code Editor', '/code-editor'],
    ['Validation', '/validation'],
]);

it('filters the catalogue by trimmed names and tags without losing destinations', function () {
    $screen = Native::visit('/')->select('query', '  tiMe  ');
    $items = showcaseNodesOfType($screen->tree(), 'list_item');

    expect(array_column(array_column($items, 'props'), 'headline'))->toBe(['Time Picker']);
    $screen->tap('Time Picker')->assertNavigatedTo('/time-picker');

    $screen = Native::visit('/')->select('query', 'firstlight:list-section');
    $items = showcaseNodesOfType($screen->tree(), 'list_item');
    expect(array_column(array_column($items, 'props'), 'headline'))->toBe(['Collection List']);
});

it('explains an empty catalogue search and lets the tester clear it', function () {
    $screen = Native::visit('/')->select('query', 'no such control');

    expect(showcaseNodesOfType($screen->tree(), 'list_item'))->toBe([]);
    $screen->assertSee('No demos match. Clear search to see every control.')
        ->tap('Clear search');
    expect(showcaseNodesOfType($screen->tree(), 'list_item'))->toHaveCount(32);
});

it('shows native back chrome on component pages but not capture routes', function () {
    foreach (['/activity-indicator', '/button', '/callout', '/transient-feedback', '/transient-feedback/destination', '/badge', '/checkbox', '/media', '/choice-group', '/alert-dialog', '/confirmation-dialog', '/modal', '/bottom-sheet', '/date-picker', '/time-picker', '/icon-button', '/list-item', '/list', '/pill-group', '/progress', '/segmented', '/search-field', '/select', '/slider', '/stepper', '/status-label', '/switch', '/tabs', '/text-field', '/text-area', '/code-editor', '/validation'] as $path) {
        expect(Native::visit($path)->tree()['props']['back'] ?? null)->toBeTrue();
    }

    expect(Native::visit('/captures/activity-indicator')->tree()['props']['back'] ?? null)->toBeFalse();
    expect(Native::visit('/captures/button')->tree()['props']['back'] ?? null)->toBeFalse();
    expect(Native::visit('/captures/callout')->tree()['props']['back'] ?? null)->toBeFalse();
    expect(Native::visit('/captures/transient-feedback')->tree()['props']['back'] ?? null)->toBeFalse();
    expect(Native::visit('/captures/badge')->tree()['props']['back'] ?? null)->toBeFalse();
    expect(Native::visit('/captures/checkbox')->tree()['props']['back'] ?? null)->toBeFalse();
    expect(Native::visit('/captures/media')->tree()['props']['back'] ?? null)->toBeFalse();
    expect(Native::visit('/captures/choice-group')->tree()['props']['back'] ?? null)->toBeFalse();
    expect(Native::visit('/captures/alert-dialog')->tree()['props']['back'] ?? null)->toBeFalse();
    expect(Native::visit('/captures/confirmation-dialog')->tree()['props']['back'] ?? null)->toBeFalse();
    expect(Native::visit('/captures/modal')->tree()['props']['back'] ?? null)->toBeFalse();
    expect(Native::visit('/captures/bottom-sheet')->tree()['props']['back'] ?? null)->toBeFalse();
    expect(Native::visit('/captures/date-picker')->tree()['props']['back'] ?? null)->toBeFalse();
    expect(Native::visit('/captures/time-picker')->tree()['props']['back'] ?? null)->toBeFalse();
    expect(Native::visit('/captures/tabs')->tree()['props']['back'] ?? null)->toBeFalse();
    expect(Native::visit('/captures/icon-button')->tree()['props']['back'] ?? null)->toBeFalse();
    expect(Native::visit('/captures/list-item')->tree()['props']['back'] ?? null)->toBeFalse();
    expect(Native::visit('/captures/list')->tree()['props']['back'] ?? null)->toBeFalse();
    expect(Native::visit('/captures/search-field')->tree()['props']['back'] ?? null)->toBeFalse();
    expect(Native::visit('/captures/select')->tree()['props']['back'] ?? null)->toBeFalse();
    expect(Native::visit('/captures/slider')->tree()['props']['back'] ?? null)->toBeFalse();
    expect(Native::visit('/captures/stepper')->tree()['props']['back'] ?? null)->toBeFalse();
    expect(Native::visit('/captures/text-area')->tree()['props']['back'] ?? null)->toBeFalse();
    expect(Native::visit('/captures/code-editor')->tree()['props']['back'] ?? null)->toBeFalse();
    expect(Native::visit('/captures/validate-fields')->tree()['props']['back'] ?? null)->toBeFalse();
});
