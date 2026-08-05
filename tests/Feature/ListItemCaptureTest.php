<?php

use Native\Mobile\Testing\Native;

/** @return list<array<string, mixed>> */
function listItemCaptureNodes(array $tree): array
{
    $nodes = ($tree['type'] ?? null) === 'firstlight.list-item' ? [$tree] : [];

    foreach ($tree['children'] ?? [] as $child) {
        $nodes = [...$nodes, ...listItemCaptureNodes($child)];
    }

    return $nodes;
}

it('publishes the stable List Item documentation capture fixture', function () {
    $screen = Native::visit('/captures/list-item');
    $nodes = listItemCaptureNodes($screen->tree());

    expect($screen->tree()['props']['title'] ?? null)->toBe('Firstlight List Item')
        ->and($nodes)->toHaveCount(4)
        ->and(array_column(array_column($nodes, 'props'), 'headline'))->toBe([
            'Account',
            'Wojt Janowski',
            'Billing',
            'Unavailable account',
        ])
        ->and($nodes[0]['props'])->toMatchArray([
            'leading_type' => 'icon',
            'trailing_type' => 'icon',
            'a11y_hint' => 'Opens account settings',
        ])
        ->and($nodes[1]['props'])->toMatchArray([
            'leading_type' => 'monogram',
            'leading_value' => 'WJ',
            'trailing_type' => 'text',
            'trailing_value' => 'Admin',
        ])
        ->and($nodes[2]['props'])->toMatchArray([
            'trailing_type' => 'text',
            'trailing_value' => 'Open',
        ])
        ->and($nodes[3]['props']['disabled'])->toBeTrue();

    $screen->assertAccessible();
});
