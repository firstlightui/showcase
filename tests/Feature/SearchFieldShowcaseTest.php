<?php

use Native\Mobile\Testing\Native;

/** @return array<string, array<string, mixed>> */
function firstlightSearchFieldsByA11yLabel(array $tree): array
{
    $nodes = [];
    if (($tree['type'] ?? null) === 'firstlight.search-field') {
        $nodes[$tree['props']['a11y_label']] = $tree;
    }
    foreach ($tree['children'] ?? [] as $child) {
        $nodes = [...$nodes, ...firstlightSearchFieldsByA11yLabel($child)];
    }
    return $nodes;
}

it('publishes the complete Search Field catalogue through the native wire tree', function () {
    $screen = Native::visit('/search-field');
    $tree = $screen->tree();
    $nodes = firstlightSearchFieldsByA11yLabel($tree);

    expect($tree['type'])->toBe('native_root_stack')
        ->and($tree['props']['title'] ?? null)->toBe('Firstlight Search Field')
        ->and(array_keys($nodes))->toBe([
            'Search referrals',
            'Search clinicians',
            'Search specialties',
            'Search archived referrals',
        ])
        ->and($nodes['Search referrals']['props'])->toMatchArray([
            'value' => 'Referral',
            'sync_mode' => 'live',
            'autocapitalize' => 'words',
            'autocorrect_policy' => 'disabled',
        ])
        ->and($nodes['Search clinicians']['props'])->toMatchArray([
            'value' => '',
            'sync_mode' => 'debounce',
            'debounce_ms' => 500,
        ])
        ->and($nodes['Search specialties']['props'])->toMatchArray([
            'value' => 'Cardiology',
            'sync_mode' => 'blur',
        ])
        ->and($nodes['Search archived referrals']['props'])->toMatchArray([
            'value' => 'Archived referral',
            'disabled' => true,
        ]);

    $screen->assertAccessible();
});

it('updates the live binding and programmatic reset', function () {
    Native::visit('/search-field')
        ->select('query', 'Neurology')
        ->assertSet('query', 'Neurology')
        ->press('resetQuery')
        ->assertSet('query', 'Referral');
});
