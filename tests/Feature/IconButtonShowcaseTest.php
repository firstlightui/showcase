<?php

use Native\Mobile\Testing\Native;

/** @return list<array<string, mixed>> */
function iconButtonShowcaseNodes(array $tree): array
{
    $nodes = ($tree['type'] ?? null) === 'firstlight.icon-button' ? [$tree] : [];

    foreach ($tree['children'] ?? [] as $child) {
        $nodes = [...$nodes, ...iconButtonShowcaseNodes($child)];
    }

    return $nodes;
}

it('publishes the complete Icon Button gallery through the native wire tree', function () {
    $screen = Native::visit('/icon-button');
    $nodes = iconButtonShowcaseNodes($screen->tree());

    expect($screen->tree()['props']['title'] ?? null)->toBe('Firstlight Icon Button')
        ->and($nodes)->toHaveCount(12)
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
        ->and($nodes[8]['props']['icon'])->toBeString()
        ->and($nodes[8]['props']['a11y_label'])->toBe('Share item')
        ->and($nodes[9]['props']['disabled'])->toBeTrue()
        ->and($nodes[10]['props']['loading'])->toBeTrue();

    foreach ($nodes as $node) {
        expect($node['on_press'] ?? null)->toBeInt();
    }

    $screen->assertAccessible();
});

it('dispatches an ordinary icon press and publishes the accepted PHP result', function () {
    $screen = Native::visit('/icon-button');
    $screen->tap('Record press');

    expect($screen->get('pressCount'))->toBe(1);
    $screen->assertSee('Recorded presses: 1');
});
