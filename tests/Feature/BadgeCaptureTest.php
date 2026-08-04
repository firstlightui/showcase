<?php

use Native\Mobile\Testing\Native;

/** @return list<array<string, mixed>> */
function badgeCaptureNodes(array $tree): array
{
    $nodes = ($tree['type'] ?? null) === 'firstlight.badge' ? [$tree] : [];

    foreach ($tree['children'] ?? [] as $child) {
        $nodes = [...$nodes, ...badgeCaptureNodes($child)];
    }

    return $nodes;
}

it('publishes the stable Badge documentation capture fixture', function () {
    $screen = Native::visit('/captures/badge');
    $nodes = badgeCaptureNodes($screen->tree());

    expect($screen->tree()['props']['title'] ?? null)->toBe('Firstlight Badge')
        ->and(array_column(array_column($nodes, 'props'), 'label'))->toBe([
            '1', '9', 'Ready', '99', '99+',
        ])
        ->and(array_column(array_column($nodes, 'props'), 'tone'))->toBe([
            'neutral', 'info', 'success', 'warning', 'danger',
        ]);

    $screen->assertAccessible();
});
