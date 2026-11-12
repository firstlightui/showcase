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
    $screen = Native::visit('/components/search-field');
    $tree = $screen->tree();
    $nodes = firstlightSearchFieldsByA11yLabel($tree);

    expect($tree['type'])->toBe('native_root_tabs')
        ->and($tree['props']['nav_title'] ?? null)->toBe('Firstlight Search Field')
        ->and(array_keys($nodes))->toBe([
            'Search referrals',
            'Search clinicians',
            'Search specialties',
            'Search wards',
            'Search approved wards',
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
    $screen = Native::visit('/components/search-field');
    $screen
        ->select('query', 'Neurology')
        ->assertSet('query', 'Neurology')
        ->assertSee('PHP accepted state: Neurology')
        ->select('debouncedQuery', 'Smith')
        ->assertSee('PHP accepted state: Smith')
        ->select('blurQuery', 'Respiratory')
        ->assertSee('PHP accepted state: Respiratory')
        ->submit('liveSearch', 'Neurology')
        ->assertSet('lastSubmitted', 'Neurology')
        ->assertSet('submitCount', 1)
        ->assertSee('Last submitted: Neurology; submissions: 1')
        ->submit('debouncedSearch', 'Smith')
        ->assertSee('Last submitted: Smith; submissions: 2')
        ->submit('blurSearch', 'Respiratory')
        ->assertSee('Last submitted: Respiratory; submissions: 3')
        ->tap('Reset query')
        ->assertSet('query', 'Referral');

    $nodes = firstlightSearchFieldsByA11yLabel($screen->tree());
    expect($nodes['Search referrals']['props']['value'])->toBe('Referral')
        ->and($nodes['Search clinicians']['props']['value'])->toBe('Smith')
        ->and($nodes['Search specialties']['props']['value'])->toBe('Respiratory');
});

it('publishes lazy synchronisation and keeps a rejected query at the approved value', function () {
    $screen = Native::visit('/components/search-field');
    $nodes = firstlightSearchFieldsByA11yLabel($screen->tree());

    expect($nodes['Search wards']['props'])->toMatchArray(['value' => '', 'sync_mode' => 'blur'])
        ->and($nodes['Search approved wards']['props'])->toMatchArray(['value' => 'Ward 4', 'sync_mode' => 'live']);

    $screen->select('lazyQuery', 'Ward 7')
        ->assertSee('PHP accepted state: Ward 7 (lazy)')
        ->submit('lazySearch', 'Ward 7')
        ->assertSee('Last submitted: Ward 7; submissions: 1')
        ->select('rejectQuery', 'Ward 9')
        ->assertSet('approvedQuery', 'Ward 4')
        ->select('rejectQuery', '')
        ->assertSet('rejectedQueries', 2)
        ->assertSee('PHP accepted state: Ward 4; Rejected queries: 2');

    expect(firstlightSearchFieldsByA11yLabel($screen->tree())['Search approved wards']['props']['value'])->toBe('Ward 4');
});
