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
        'Segmented',
        'Status Label',
        'Text Field',
    ])->and(array_column(array_column($items, 'props'), 'overline'))->toBe([
        '<firstlight:button>',
        '<firstlight:segmented>',
        '<firstlight:status-label>',
        '<firstlight:text-field>',
    ])->and(array_column(array_column($items, 'props'), 'supporting'))->toBe([
        'Labelled actions, variants, sizes, icons, and states',
        'Server-authoritative single selection',
        'Compact semantic status metadata',
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
    ['Segmented', '/segmented'],
    ['Status Label', '/status-label'],
    ['Text Field', '/text-field'],
]);

it('shows native back chrome on component pages but not capture routes', function () {
    foreach (['/button', '/segmented', '/status-label', '/text-field'] as $path) {
        expect(Native::visit($path)->tree()['props']['back'] ?? null)->toBeTrue();
    }

    expect(Native::visit('/captures/button')->tree()['props']['back'] ?? null)->toBeFalse();
});
