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
        'Button',
        'Badge',
        'Icon Button',
        'Pill Group',
        'Progress',
        'Segmented',
        'Status Label',
        'Switch',
        'Text Field',
    ])->and(array_column(array_column($items, 'props'), 'overline'))->toBe([
        '<firstlight:button>',
        '<firstlight:badge>',
        '<firstlight:icon-button>',
        '<firstlight:pill-group>',
        '<firstlight:progress>',
        '<firstlight:segmented>',
        '<firstlight:status-label>',
        '<firstlight:switch>',
        '<firstlight:text-field>',
    ])->and(array_column(array_column($items, 'props'), 'supporting'))->toBe([
        'Labelled actions, variants, sizes, icons, and states',
        'Compact display-only counts and semantic markers',
        'Compact accessible actions with native icon controls',
        'Compact single- and multiple-selection options',
        'Determinate and indeterminate work state',
        'Server-authoritative single selection',
        'Compact semantic status metadata',
        'Server-authoritative boolean settings',
        'Native text entry, validation, and affordances',
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
    ['Button', '/button'],
    ['Badge', '/badge'],
    ['Icon Button', '/icon-button'],
    ['Pill Group', '/pill-group'],
    ['Progress', '/progress'],
    ['Segmented', '/segmented'],
    ['Status Label', '/status-label'],
    ['Switch', '/switch'],
    ['Text Field', '/text-field'],
]);

it('shows native back chrome on component pages but not capture routes', function () {
    foreach (['/button', '/badge', '/icon-button', '/pill-group', '/progress', '/segmented', '/status-label', '/switch', '/text-field'] as $path) {
        expect(Native::visit($path)->tree()['props']['back'] ?? null)->toBeTrue();
    }

    expect(Native::visit('/captures/button')->tree()['props']['back'] ?? null)->toBeFalse();
    expect(Native::visit('/captures/badge')->tree()['props']['back'] ?? null)->toBeFalse();
    expect(Native::visit('/captures/icon-button')->tree()['props']['back'] ?? null)->toBeFalse();
});
