<?php

use Native\Mobile\Testing\Native;

/** @return list<array<string, mixed>> */
function buttonCaptureNodes(array $tree): array
{
    $nodes = ($tree['type'] ?? null) === 'firstlight.button' ? [$tree] : [];

    foreach ($tree['children'] ?? [] as $child) {
        $nodes = [...$nodes, ...buttonCaptureNodes($child)];
    }

    return $nodes;
}

it('publishes the stable Button documentation capture fixture', function () {
    $screen = Native::visit('/captures/button');
    $nodes = buttonCaptureNodes($screen->tree());

    expect($screen->tree()['props']['title'] ?? null)->toBe('Firstlight Button')
        ->and(array_column(array_column($nodes, 'props'), 'label'))->toBe([
            'Primary action',
            'Secondary action',
            'Delete draft',
            'Confirm selection',
            'Ghost action',
            'Add item',
            'Continue',
            'Unavailable',
            'Saving changes',
        ])
        ->and($nodes[5]['props']['leading_icon'])->toBe('plus')
        ->and($nodes[6]['props']['trailing_icon'])->toBe('chevron-right')
        ->and($nodes[7]['props']['disabled'])->toBeTrue()
        ->and($nodes[8]['props']['loading'])->toBeTrue();

    $screen->assertAccessible();
});
