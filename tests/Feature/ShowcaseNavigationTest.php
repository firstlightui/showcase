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
    ])->and(array_column(array_column($items, 'props'), 'headline'))->toBe([
        'Activity Indicator',
        'Button',
        'Callout',
        'Badge',
        'Checkbox',
        'Choice Group',
        'Confirmation Dialog',
        'Date Picker',
        'Time Picker',
        'Icon Button',
        'List Item',
        'Pill Group',
        'Progress',
        'Segmented',
        'Search Field',
        'Select',
        'Slider',
        'Stepper',
        'Status Label',
        'Switch',
        'Text Field',
        'Text Area',
    ])->and(array_column(array_column($items, 'props'), 'overline'))->toBe([
        '<firstlight:activity-indicator>',
        '<firstlight:button>',
        '<firstlight:callout>',
        '<firstlight:badge>',
        '<firstlight:checkbox>',
        '<firstlight:choice-group>',
        '<firstlight:confirmation-dialog>',
        '<firstlight:date-picker>',
        '<firstlight:time-picker>',
        '<firstlight:icon-button>',
        '<firstlight:list-item>',
        '<firstlight:pill-group>',
        '<firstlight:progress>',
        '<firstlight:segmented>',
        '<firstlight:search-field>',
        '<firstlight:select>',
        '<firstlight:slider>',
        '<firstlight:stepper>',
        '<firstlight:status-label>',
        '<firstlight:switch>',
        '<firstlight:text-field>',
        '<firstlight:text-area>',
    ])->and(array_column(array_column($items, 'props'), 'supporting'))->toBe([
        'Indeterminate native activity with semantic sizes',
        'Labelled actions, variants, sizes, icons, and states',
        'Persistent semantic messages with an optional action',
        'Compact display-only counts and semantic markers',
        'Server-authoritative Boolean form and checklist state',
        'Visible single-radio and multiple-checkbox choices',
        'Native confirmation, cancellation, and destructive roles',
        'Nullable calendar dates, bounds, and native confirmation',
        'Nullable wall-clock times and native confirmation',
        'Compact accessible actions with native icon controls',
        'Tappable application rows with identity and affordances',
        'Compact single- and multiple-selection options',
        'Determinate and indeterminate work state',
        'Server-authoritative single selection',
        'Native query entry, clear, and submission behaviour',
        'Stable single selection with automatic search',
        'Strict stepped numeric ranges with native gestures',
        'Exact bounded increments with server authority',
        'Compact semantic status metadata',
        'Server-authoritative boolean settings',
        'Native text entry, validation, and affordances',
        'Native multiline editing, validation, and synchronisation',
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
})->with([
    ['Activity Indicator', '/activity-indicator'],
    ['Button', '/button'],
    ['Callout', '/callout'],
    ['Badge', '/badge'],
    ['Checkbox', '/checkbox'],
    ['Choice Group', '/choice-group'],
    ['Confirmation Dialog', '/confirmation-dialog'],
    ['Date Picker', '/date-picker'],
    ['Time Picker', '/time-picker'],
    ['Icon Button', '/icon-button'],
    ['List Item', '/list-item'],
    ['Pill Group', '/pill-group'],
    ['Progress', '/progress'],
    ['Segmented', '/segmented'],
    ['Search Field', '/search-field'],
    ['Select', '/select'],
    ['Slider', '/slider'],
    ['Stepper', '/stepper'],
    ['Status Label', '/status-label'],
    ['Switch', '/switch'],
    ['Text Field', '/text-field'],
    ['Text Area', '/text-area'],
]);

it('shows native back chrome on component pages but not capture routes', function () {
    foreach (['/activity-indicator', '/button', '/callout', '/badge', '/checkbox', '/choice-group', '/confirmation-dialog', '/date-picker', '/time-picker', '/icon-button', '/list-item', '/pill-group', '/progress', '/segmented', '/search-field', '/select', '/slider', '/stepper', '/status-label', '/switch', '/text-field', '/text-area'] as $path) {
        expect(Native::visit($path)->tree()['props']['back'] ?? null)->toBeTrue();
    }

    expect(Native::visit('/captures/activity-indicator')->tree()['props']['back'] ?? null)->toBeFalse();
    expect(Native::visit('/captures/button')->tree()['props']['back'] ?? null)->toBeFalse();
    expect(Native::visit('/captures/callout')->tree()['props']['back'] ?? null)->toBeFalse();
    expect(Native::visit('/captures/badge')->tree()['props']['back'] ?? null)->toBeFalse();
    expect(Native::visit('/captures/checkbox')->tree()['props']['back'] ?? null)->toBeFalse();
    expect(Native::visit('/captures/choice-group')->tree()['props']['back'] ?? null)->toBeFalse();
    expect(Native::visit('/captures/confirmation-dialog')->tree()['props']['back'] ?? null)->toBeFalse();
    expect(Native::visit('/captures/date-picker')->tree()['props']['back'] ?? null)->toBeFalse();
    expect(Native::visit('/captures/time-picker')->tree()['props']['back'] ?? null)->toBeFalse();
    expect(Native::visit('/captures/icon-button')->tree()['props']['back'] ?? null)->toBeFalse();
    expect(Native::visit('/captures/list-item')->tree()['props']['back'] ?? null)->toBeFalse();
    expect(Native::visit('/captures/search-field')->tree()['props']['back'] ?? null)->toBeFalse();
    expect(Native::visit('/captures/select')->tree()['props']['back'] ?? null)->toBeFalse();
    expect(Native::visit('/captures/slider')->tree()['props']['back'] ?? null)->toBeFalse();
    expect(Native::visit('/captures/stepper')->tree()['props']['back'] ?? null)->toBeFalse();
    expect(Native::visit('/captures/text-area')->tree()['props']['back'] ?? null)->toBeFalse();
});
