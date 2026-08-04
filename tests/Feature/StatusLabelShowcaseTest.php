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
    $screen = Native::visit('/status-label');
    $nodes = statusLabelShowcaseNodes($screen->tree());

    expect($screen->tree()['props']['title'] ?? null)->toBe('Firstlight Status Label')
        ->and(array_column(array_column($nodes, 'props'), 'label'))->toBe([
            'Draft',
            'In progress',
            'Ready',
            'Awaiting review',
            'Blocked',
            'Referral status: awaiting review from the referrals team',
            'Screen-reader override',
        ])
        ->and(array_column(array_column($nodes, 'props'), 'tone'))->toBe([
            'neutral',
            'info',
            'success',
            'warning',
            'danger',
            'warning',
            'success',
        ])
        ->and($nodes[0]['props'])->toBe([
            'label' => 'Draft',
            'tone' => 'neutral',
        ])
        ->and($nodes[6]['props']['a11y_label'])->toBe('Referral status: ready')
        ->and($nodes[6]['props']['a11y_hint'])->toBe('Updated by the referrals team');

    $screen->assertAccessible();
});

it('publishes no callback or mutable state props', function () {
    foreach (statusLabelShowcaseNodes(Native::visit('/status-label')->tree()) as $node) {
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
