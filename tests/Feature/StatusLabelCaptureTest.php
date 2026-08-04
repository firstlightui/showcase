<?php

use Native\Mobile\Testing\Native;

/** @return list<array<string, mixed>> */
function statusLabelCaptureNodes(array $tree): array
{
    $nodes = ($tree['type'] ?? null) === 'firstlight.status-label' ? [$tree] : [];

    foreach ($tree['children'] ?? [] as $child) {
        $nodes = [...$nodes, ...statusLabelCaptureNodes($child)];
    }

    return $nodes;
}

it('publishes the stable Status Label documentation capture fixture', function () {
    $screen = Native::visit('/captures/status-label');
    $nodes = statusLabelCaptureNodes($screen->tree());

    expect($screen->tree()['props']['title'] ?? null)->toBe('Firstlight Status Label')
        ->and(array_column(array_column($nodes, 'props'), 'label'))->toBe([
            'Draft',
            'In progress',
            'Ready',
            'Awaiting review',
            'Blocked',
        ])
        ->and(array_column(array_column($nodes, 'props'), 'tone'))->toBe([
            'neutral',
            'info',
            'success',
            'warning',
            'danger',
        ]);

    $screen->assertAccessible();
});
