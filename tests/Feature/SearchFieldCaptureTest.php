<?php

use Native\Mobile\Testing\Native;

/** @return list<array<string, mixed>> */
function searchFieldCaptureNodes(array $tree): array
{
    $nodes = ($tree['type'] ?? null) === 'firstlight.search-field' ? [$tree] : [];
    foreach ($tree['children'] ?? [] as $child) {
        $nodes = [...$nodes, ...searchFieldCaptureNodes($child)];
    }
    return $nodes;
}

it('publishes the stable Search Field documentation capture fixture', function () {
    $screen = Native::visit('/captures/search-field');
    $tree = $screen->tree();
    $nodes = searchFieldCaptureNodes($tree);

    expect($tree['props']['title'] ?? null)->toBe('Firstlight Search Field')
        ->and($nodes)->toHaveCount(3)
        ->and(array_column(array_column($nodes, 'props'), 'a11y_label'))->toBe([
            'Search referrals',
            'Search clinicians',
            'Search archived referrals',
        ])
        ->and($nodes[0]['props'])->toMatchArray([
            'value' => 'Referral',
            'placeholder' => 'Search referrals',
            'autocapitalize' => 'words',
            'autocorrect_policy' => 'disabled',
        ])
        ->and($nodes[1]['props']['value'])->toBe('')
        ->and($nodes[2]['props']['disabled'])->toBeTrue();

    $screen->assertAccessible();
});
