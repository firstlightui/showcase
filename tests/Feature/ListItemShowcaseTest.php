<?php

use Native\Mobile\Testing\Native;

/** @return list<array<string, mixed>> */
function listItemShowcaseNodes(array $tree): array
{
    $nodes = ($tree['type'] ?? null) === 'firstlight.list-item' ? [$tree] : [];

    foreach ($tree['children'] ?? [] as $child) {
        $nodes = [...$nodes, ...listItemShowcaseNodes($child)];
    }

    return $nodes;
}

it('publishes every List Item content and state fixture through the native wire tree', function () {
    $screen = Native::visit('/list-item');
    $nodes = listItemShowcaseNodes($screen->tree());

    expect($screen->tree()['props']['title'] ?? null)->toBe('Firstlight List Item')
        ->and($nodes)->toHaveCount(6)
        ->and(array_column(array_column($nodes, 'props'), 'headline'))->toBe([
            'Account',
            'Billing',
            'Profile',
            'Team',
            'Notifications',
            'Managed account',
        ])
        ->and($nodes[0]['props'])->not->toHaveKeys(['supporting', 'leading_type', 'trailing_type'])
        ->and($nodes[1]['props'])->toMatchArray([
            'supporting' => 'Invoices and payment methods',
            'trailing_type' => 'text',
            'trailing_value' => 'Open',
        ])
        ->and($nodes[2]['props'])->toMatchArray([
            'leading_type' => 'avatar',
            'leading_value' => 'https://placehold.co/80x80/png?text=AJ',
        ])
        ->and($nodes[3]['props'])->toMatchArray([
            'leading_type' => 'monogram',
            'leading_value' => 'FL',
            'trailing_type' => 'text',
        ])
        ->and($nodes[4]['props'])->toMatchArray([
            'leading_type' => 'icon',
            'trailing_type' => 'icon',
            'a11y_hint' => 'Opens notification preferences',
        ])
        ->and($nodes[5]['props']['disabled'])->toBeTrue();

    foreach ($nodes as $node) {
        expect($node['on_press'] ?? null)->toBeInt();
    }

    $screen->assertAccessible();
});

it('dispatches ordinary row presses and publishes the PHP result', function () {
    $screen = Native::visit('/list-item');
    $screen->tap('Account');
    $screen->tap('Team');

    expect($screen->get('pressCount'))->toBe(2)
        ->and($screen->get('lastPressed'))->toBe('Team');
    $screen->assertSee('Recorded presses: 2');
    $screen->assertSee('Last row: Team');
});
