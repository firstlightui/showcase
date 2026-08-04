<?php

use Native\Mobile\Testing\Native;

/** @return list<array<string, mixed>> */
function iconButtonCaptureNodes(array $tree): array
{
    $nodes = ($tree['type'] ?? null) === 'firstlight.icon-button' ? [$tree] : [];

    foreach ($tree['children'] ?? [] as $child) {
        $nodes = [...$nodes, ...iconButtonCaptureNodes($child)];
    }

    return $nodes;
}

it('publishes the stable Icon Button documentation capture fixture', function () {
    $screen = Native::visit('/captures/icon-button');
    $nodes = iconButtonCaptureNodes($screen->tree());

    expect($screen->tree()['props']['title'] ?? null)->toBe('Firstlight Icon Button')
        ->and($nodes)->toHaveCount(11)
        ->and(array_column(array_slice(array_column($nodes, 'props'), 0, 5), 'variant'))->toBe([
            'primary',
            'secondary',
            'destructive',
            'success',
            'ghost',
        ])
        ->and(array_column(array_slice(array_column($nodes, 'props'), 5, 3), 'size'))->toBe([
            'sm',
            'md',
            'lg',
        ])
        ->and($nodes[9]['props']['disabled'])->toBeTrue()
        ->and($nodes[10]['props']['loading'])->toBeTrue();

    $screen->assertAccessible();
});
