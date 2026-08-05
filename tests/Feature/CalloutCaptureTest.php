<?php

use Native\Mobile\Testing\Native;

/** @return list<array<string, mixed>> */
function calloutCaptureNodes(array $tree): array
{
    $nodes = ($tree['type'] ?? null) === 'firstlight.callout' ? [$tree] : [];

    foreach ($tree['children'] ?? [] as $child) {
        $nodes = [...$nodes, ...calloutCaptureNodes($child)];
    }

    return $nodes;
}

it('publishes the stable Callout documentation capture fixture', function () {
    $screen = Native::visit('/captures/callout');
    $nodes = calloutCaptureNodes($screen->tree());

    expect($screen->tree()['props']['title'] ?? null)->toBe('Firstlight Callout')
        ->and(array_column(array_column($nodes, 'props'), 'tone'))->toBe([
            'neutral', 'info', 'success', 'warning', 'danger',
        ])
        ->and($nodes[3]['props']['action_label'])->toBe('Review changes')
        ->and($nodes[3]['on_press'] ?? null)->toBeInt();

    $screen->assertAccessible();
});
