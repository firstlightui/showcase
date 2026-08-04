<?php

use Native\Mobile\Testing\Native;

/** @return list<array<string, mixed>> */
function segmentedCaptureNodes(array $tree): array
{
    $nodes = ($tree['type'] ?? null) === 'firstlight.segmented' ? [$tree] : [];

    foreach ($tree['children'] ?? [] as $child) {
        $nodes = [...$nodes, ...segmentedCaptureNodes($child)];
    }

    return $nodes;
}

it('publishes the stable Segmented documentation capture fixture', function () {
    $tree = Native::visit('/captures/segmented')->tree();
    $nodes = segmentedCaptureNodes($tree);

    expect($tree['props']['title'] ?? null)->toBe('Firstlight Segmented')
        ->and($nodes)->toHaveCount(3)
        ->and(array_column(array_column($nodes, 'props'), 'label'))->toBe([
            'Queue',
            'Priority',
            'Queue with error',
        ])
        ->and($nodes[0]['props']['selected_value'])->toBe('mine')
        ->and($nodes[0]['props']['helper'])->toBe('Choose the active queue.')
        ->and($nodes[0]['props']['a11y_hint'])->toBe('Changes the active queue')
        ->and($nodes[1]['props']['selected_value'])->toBe('10')
        ->and($nodes[1]['props']['option_values'])->toBe(['10', '20'])
        ->and($nodes[1]['props']['option_enabled'])->toBe(['1', '0'])
        ->and($nodes[1]['props']['a11y_hint'])->toBe('Urgent is unavailable')
        ->and($nodes[2]['props']['selected_value'])->toBe('all')
        ->and($nodes[2]['props']['error'])->toBe('Choose Mine before continuing.')
        ->and($nodes[2]['props']['a11y_hint'])->toBe('Resolve the queue error');

    Native::visit('/captures/segmented')->assertAccessible();
});
