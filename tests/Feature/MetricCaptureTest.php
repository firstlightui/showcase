<?php

use Native\Mobile\Testing\Native;

function metricCaptureNodes(array $tree): array
{
    $nodes = ($tree['type'] ?? null) === 'firstlight.metric' ? [$tree] : [];
    foreach ($tree['children'] ?? [] as $child) {
        $nodes = [...$nodes, ...metricCaptureNodes($child)];
    }

    return $nodes;
}

it('publishes the stable Metric capture through real Blade registration', function () {
    $screen = Native::visit('/captures/metric');
    $nodes = metricCaptureNodes($screen->tree());
    expect(array_column(array_column($nodes, 'props'), 'value'))->toBe(['5', '0', '234 ms', '12', '3'])
        ->and($nodes[0]['props']['a11y_label'])->toBe('Events: 5, last 10h ago')
        ->and($nodes[3]['props']['a11y_label'])->toBe('Pending: 12');
    $screen->assertAccessible();
});

it('publishes long values and accessible overrides in the Metric gallery', function () {
    $screen = Native::visit('/components/metric');
    $nodes = metricCaptureNodes($screen->tree());
    expect($nodes[1]['props']['a11y_label'])->toBe('No affected users')
        ->and($nodes[2]['props']['value'])->toBe('1,234.50 milliseconds');
    $screen->assertAccessible();
});
