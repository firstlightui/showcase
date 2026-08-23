<?php

use Native\Mobile\Testing\Native;

/** @return list<array<string, mixed>> */
function listCaptureNodes(array $tree): array
{
    $nodes = ($tree['type'] ?? null) === 'firstlight.list' ? [$tree] : [];

    foreach ($tree['children'] ?? [] as $child) {
        $nodes = [...$nodes, ...listCaptureNodes($child)];
    }

    return $nodes;
}

it('publishes the stable list capture fixture through the native wire tree', function () {
    $screen = Native::visit('/captures/list');
    $lists = listCaptureNodes($screen->tree());

    expect($screen->tree()['props']['back'] ?? null)->toBeFalse()
        ->and($lists)->toHaveCount(1)
        ->and($lists[0]['props']['separator'] ?? null)->toBeTrue();

    $sections = array_values(array_filter(
        $lists[0]['children'] ?? [],
        fn (array $child): bool => ($child['type'] ?? null) === 'list_section',
    ));

    expect($sections)->toHaveCount(1)
        ->and($sections[0]['props']['header'] ?? null)->toBe('Preferences');

    $screen->assertAccessible();
});
