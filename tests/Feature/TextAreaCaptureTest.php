<?php

use Native\Mobile\Testing\Native;

/** @return list<array<string, mixed>> */
function textAreaCaptureNodes(array $tree): array
{
    $nodes = ($tree['type'] ?? null) === 'firstlight.text-area' ? [$tree] : [];
    foreach ($tree['children'] ?? [] as $child) {
        $nodes = [...$nodes, ...textAreaCaptureNodes($child)];
    }
    return $nodes;
}

it('publishes the stable Text Area documentation capture fixture', function () {
    $screen = Native::visit('/captures/text-area');
    $tree = $screen->tree();
    $nodes = textAreaCaptureNodes($tree);

    expect($tree['props']['title'] ?? null)->toBe('Firstlight Text Area')
        ->and($nodes)->toHaveCount(3)
        ->and(array_column(array_column($nodes, 'props'), 'label'))->toBe([
            'Clinical notes',
            'Note with error',
            'Read-only note',
        ])
        ->and($nodes[0]['props'])->toMatchArray([
            'required' => true,
            'min_lines' => 4,
            'max_lines' => 8,
            'sync_mode' => 'live',
        ])
        ->and($nodes[1]['props']['error'])->toBe('Add at least one observation.')
        ->and($nodes[2]['props']['read_only'])->toBeTrue();

    $screen->assertAccessible();
});
