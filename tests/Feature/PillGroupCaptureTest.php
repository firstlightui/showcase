<?php

use Native\Mobile\Testing\Native;

/** @return list<array<string, mixed>> */
function pillGroupCaptureNodes(array $tree): array
{
    $nodes = ($tree['type'] ?? null) === 'firstlight.pill-group' ? [$tree] : [];

    foreach ($tree['children'] ?? [] as $child) {
        $nodes = [...$nodes, ...pillGroupCaptureNodes($child)];
    }

    return $nodes;
}

it('publishes the stable Pill Group documentation capture fixture', function () {
    $screen = Native::visit('/captures/pill-group');
    $nodes = pillGroupCaptureNodes($screen->tree());

    expect($screen->tree()['props']['title'] ?? null)->toBe('Firstlight Pill Group')
        ->and($nodes)->toHaveCount(3)
        ->and(array_column(array_column($nodes, 'props'), 'label'))->toBe([
            'Queue',
            'Queues',
            'Workflow status',
        ])
        ->and($nodes[0]['props']['selected_values'])->toBe(['mine'])
        ->and($nodes[0]['props']['a11y_hint'])->toBe('Tap the selected queue to clear it')
        ->and($nodes[1]['props']['selected_values'])->toBe(['mine', 'urgent'])
        ->and($nodes[1]['props']['multiple'])->toBeTrue()
        ->and($nodes[1]['props']['required'])->toBeTrue()
        ->and($nodes[2]['props']['selected_values'])->toBe([])
        ->and($nodes[2]['props']['option_enabled'])->toBe(['1', '0', '1'])
        ->and($nodes[2]['props']['error'])->toBe('Choose a workflow status.');

    $screen->assertAccessible();
});
