<?php

use Native\Mobile\Testing\Native;

/** @return list<array<string, mixed>> */
function buttonShowcaseNodes(array $tree): array
{
    $nodes = ($tree['type'] ?? null) === 'firstlight.button' ? [$tree] : [];

    foreach ($tree['children'] ?? [] as $child) {
        $nodes = [...$nodes, ...buttonShowcaseNodes($child)];
    }

    return $nodes;
}

it('publishes the complete Button gallery through the native wire tree', function () {
    $screen = Native::visit('/button');
    $nodes = buttonShowcaseNodes($screen->tree());

    expect($screen->tree()['props']['title'] ?? null)->toBe('Firstlight Button')
        ->and(array_column(array_column($nodes, 'props'), 'label'))->toBe([
            'Primary action',
            'Secondary action',
            'Delete draft',
            'Confirm selection',
            'Ghost action',
            'Small',
            'Medium',
            'Large',
            'Add item',
            'Continue',
            'Unavailable',
            'Saving changes',
            'Record press',
        ])
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
        ->and($nodes[8]['props']['leading_icon'])->toBe('plus')
        ->and($nodes[9]['props']['trailing_icon'])->toBe('chevron-right')
        ->and($nodes[10]['props']['disabled'])->toBeTrue()
        ->and($nodes[11]['props']['loading'])->toBeTrue();

    $screen->assertAccessible();
});

it('dispatches an ordinary press and publishes the accepted PHP result', function () {
    $screen = Native::visit('/button');
    $screen->tap('Record press');

    expect($screen->get('pressCount'))->toBe(1);
    $screen->assertSee('Recorded presses: 1');
});

it('does not register callbacks for disabled and loading examples', function () {
    $nodes = buttonShowcaseNodes(Native::visit('/button')->tree());

    expect($nodes[10]['props'])->not->toHaveKey('on_press')
        ->and($nodes[11]['props'])->not->toHaveKey('on_press');
});
