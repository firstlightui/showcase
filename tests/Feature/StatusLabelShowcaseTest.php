<?php

use Native\Mobile\Testing\Native;

/** @return list<array<string, mixed>> */
function statusLabelShowcaseNodes(array $tree): array
{
    $nodes = ($tree['type'] ?? null) === 'firstlight.status-label' ? [$tree] : [];

    foreach ($tree['children'] ?? [] as $child) {
        $nodes = [...$nodes, ...statusLabelShowcaseNodes($child)];
    }

    return $nodes;
}

it('publishes the complete display-only Status Label gallery', function () {
    $screen = Native::visit('/components/status-label');
    $nodes = statusLabelShowcaseNodes($screen->tree());

    expect($screen->tree()['props']['nav_title'] ?? null)->toBe('Firstlight Status Label')
        ->and(array_column(array_column($nodes, 'props'), 'label'))->toBe([
            'Draft',
            'In progress',
            'Ready',
            'Awaiting review',
            'Blocked',
            'Referral status: awaiting review from the referrals team',
            'Claim drafted',
            'Screen-reader override',
        ])
        ->and(array_column(array_column($nodes, 'props'), 'tone'))->toBe([
            'neutral',
            'info',
            'success',
            'warning',
            'danger',
            'warning',
            'neutral',
            'success',
        ])
        ->and($nodes[0]['props'])->toBe([
            'label' => 'Draft',
            'tone' => 'neutral',
        ])
        ->and($nodes[7]['props']['a11y_label'])->toBe('Referral status: ready')
        ->and($nodes[7]['props']['a11y_hint'])->toBe('Updated by the referrals team');

    $screen->assertAccessible();
});

it('publishes no callback or mutable state props', function () {
    foreach (statusLabelShowcaseNodes(Native::visit('/components/status-label')->tree()) as $node) {
        expect($node['props'])->not->toHaveKeys([
            'value',
            'disabled',
            'loading',
            'error',
            'on_change',
            'on_press',
        ]);
    }
});

it('replaces the label, tone and icon of the same Status Label through later publications', function () {
    $screen = Native::visit('/components/status-label');
    $claim = fn () => statusLabelShowcaseNodes($screen->tree())[6];
    $initial = $claim();

    expect($initial['props'])->toBe(['label' => 'Claim drafted', 'tone' => 'neutral']);

    $screen->tap('Next claim status')->assertSet('claimStep', 1);
    expect($claim()['props'])->toMatchArray(['label' => 'Claim submitted', 'tone' => 'info'])
        ->and($claim()['props'])->toHaveKey('icon')
        ->and($claim()['id'])->toBe($initial['id']);

    $screen->tap('Next claim status')->tap('Next claim status')->assertSet('claimStep', 3)
        ->assertSee('PHP accepted state: Claim rejected · tone danger');
    expect($claim()['props'])->toMatchArray(['label' => 'Claim rejected', 'tone' => 'danger'])
        ->and($claim()['props'])->not->toHaveKeys(['on_change', 'on_press']);

    $screen->tap('Next claim status')->assertSet('claimStep', 0);
    expect($claim()['props'])->toBe(['label' => 'Claim drafted', 'tone' => 'neutral']);

    $screen->assertAccessible();
});
