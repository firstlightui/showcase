<?php

use Native\Mobile\Testing\Native;

/** @return list<array<string, mixed>> */
function progressCaptureNodes(array $tree): array
{
    $nodes = ($tree['type'] ?? null) === 'firstlight.progress' ? [$tree] : [];

    foreach ($tree['children'] ?? [] as $child) {
        $nodes = [...$nodes, ...progressCaptureNodes($child)];
    }

    return $nodes;
}

it('publishes the stable Progress documentation capture fixture', function () {
    $screen = Native::visit('/captures/progress');
    $nodes = progressCaptureNodes($screen->tree());

    expect($screen->tree()['props']['title'] ?? null)->toBe('Firstlight Progress')
        ->and(count($nodes))->toBe(5)
        ->and(array_column(array_column($nodes, 'props'), 'value'))->toBe([
            0.0,
            0.25,
            0.5,
            0.75,
            1.0,
        ]);

    $screen->assertAccessible();
});
