<?php

use Native\Mobile\Testing\Native;

/** @return list<array<string, mixed>> */
function listShowcaseNodes(array $tree): array
{
    $nodes = ($tree['type'] ?? null) === 'firstlight.list' ? [$tree] : [];

    foreach ($tree['children'] ?? [] as $child) {
        $nodes = [...$nodes, ...listShowcaseNodes($child)];
    }

    return $nodes;
}

/** @return list<array<string, mixed>> */
function listShowcaseItemNodes(array $tree): array
{
    $nodes = ($tree['type'] ?? null) === 'firstlight.list-item' ? [$tree] : [];

    foreach ($tree['children'] ?? [] as $child) {
        $nodes = [...$nodes, ...listShowcaseItemNodes($child)];
    }

    return $nodes;
}

it('publishes grouped list fixtures through the native wire tree', function () {
    $screen = Native::visit('/list');
    $lists = listShowcaseNodes($screen->tree());
    $items = listShowcaseItemNodes($screen->tree());

    expect($screen->tree()['props']['title'] ?? null)->toBe('Firstlight List')
        ->and($lists)->toHaveCount(1)
        ->and($lists[0]['props']['separator'] ?? null)->toBeTrue()
        ->and($lists[0]['props']['on_refresh'] ?? null)->toBeInt()
        ->and($items)->toHaveCount(3)
        ->and(array_column(array_column($items, 'props'), 'headline'))->toBe([
            'Account',
            'Notifications',
            'Privacy',
        ]);

    $sections = array_values(array_filter(
        $lists[0]['children'] ?? [],
        fn (array $child): bool => ($child['type'] ?? null) === 'list_section',
    ));

    expect($sections)->toHaveCount(1)
        ->and($sections[0]['props'])->toMatchArray([
            'header' => 'Preferences',
            'footer' => 'Changes sync automatically',
        ]);

    foreach ($items as $item) {
        expect($item['on_press'] ?? null)->toBeInt();
    }

    $screen->assertAccessible();
});

it('dispatches row presses and refresh callbacks through PHP', function () {
    $screen = Native::visit('/list');
    $screen->tap('Privacy');

    expect($screen->get('pressCount'))->toBe(1)
        ->and($screen->get('lastPressed'))->toBe('Privacy');
});
