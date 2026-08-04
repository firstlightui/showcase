<?php

use Native\Mobile\Testing\Native;

/** @return list<array<string, mixed>> */
function activityIndicatorCaptureNodes(array $tree): array
{
    $nodes = ($tree['type'] ?? null) === 'firstlight.activity-indicator' ? [$tree] : [];

    foreach ($tree['children'] ?? [] as $child) {
        $nodes = [...$nodes, ...activityIndicatorCaptureNodes($child)];
    }

    return $nodes;
}

it('publishes the stable Activity Indicator documentation capture fixture', function () {
    $screen = Native::visit('/captures/activity-indicator');
    $nodes = activityIndicatorCaptureNodes($screen->tree());

    expect($screen->tree()['props']['title'] ?? null)->toBe('Firstlight Activity Indicator')
        ->and(array_map(
            fn (array $node): array => [
                'size' => $node['props']['size'],
                'a11y_label' => $node['props']['a11y_label'],
            ],
            $nodes,
        ))->toBe([
            [
                'size' => 'sm',
                'a11y_label' => 'Loading compact appointment summary',
            ],
            [
                'size' => 'md',
                'a11y_label' => 'Loading appointments',
            ],
            [
                'size' => 'lg',
                'a11y_label' => 'Loading detailed appointment timeline',
            ],
        ]);

    $screen->assertAccessible();
});
