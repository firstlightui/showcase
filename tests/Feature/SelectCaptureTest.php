<?php

use Native\Mobile\Testing\Native;

/** @return list<array<string, mixed>> */
function selectCaptureNodes(array $tree): array
{
    $nodes = ($tree['type'] ?? null) === 'firstlight.select' ? [$tree] : [];
    foreach ($tree['children'] ?? [] as $child) {
        $nodes = [...$nodes, ...selectCaptureNodes($child)];
    }

    return $nodes;
}

it('publishes the stable Select documentation capture fixture', function () {
    $screen = Native::visit('/captures/select');
    $tree = $screen->tree();
    $nodes = selectCaptureNodes($tree);

    expect($tree['props']['title'] ?? null)->toBe('Firstlight Select')
        ->and($nodes)->toHaveCount(4)
        ->and(array_column(array_column($nodes, 'props'), 'label'))->toBe([
            'Priority',
            'Triage level',
            'Large queue',
            'Required priority',
        ])
        ->and($nodes[0]['props'])->toMatchArray([
            'selected_values' => [],
            'placeholder' => 'Select a priority',
            'search_enabled' => false,
        ])
        ->and($nodes[1]['props'])->toMatchArray([
            'selected_values' => ['20'],
            'value_type' => 'integer',
            'required' => true,
        ])
        ->and($nodes[2]['props'])->toMatchArray([
            'selected_values' => ['queue-4'],
            'search_enabled' => true,
        ])
        ->and($nodes[3]['props']['error'])->toBe('Choose a priority.');

    $screen->assertAccessible();
});
