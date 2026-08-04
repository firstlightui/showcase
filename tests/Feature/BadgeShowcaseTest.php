<?php

use Native\Mobile\Testing\Native;

/** @return list<array<string, mixed>> */
function badgeShowcaseNodes(array $tree): array
{
    $nodes = ($tree['type'] ?? null) === 'firstlight.badge' ? [$tree] : [];

    foreach ($tree['children'] ?? [] as $child) {
        $nodes = [...$nodes, ...badgeShowcaseNodes($child)];
    }

    return $nodes;
}

it('publishes count boundaries, semantic tones, and accessible marker text', function () {
    $screen = Native::visit('/badge');
    $nodes = badgeShowcaseNodes($screen->tree());

    expect($screen->tree()['props']['title'] ?? null)->toBe('Firstlight Badge')
        ->and($nodes)->toHaveCount(11)
        ->and(array_column(array_slice(array_column($nodes, 'props'), 0, 5), 'label'))->toBe([
            '', '1', '9', '99', '99+',
        ])
        ->and(array_column(array_slice(array_column($nodes, 'props'), 5, 5), 'tone'))->toBe([
            'neutral', 'info', 'success', 'warning', 'danger',
        ])
        ->and($nodes[10]['props']['a11y_label'])->toBe('Prescription ready')
        ->and($nodes[10]['props']['a11y_hint'])->toBe('Open the prescription to review it');

    $screen->assertAccessible();
});

it('publishes no callback or mutable state props', function () {
    foreach (badgeShowcaseNodes(Native::visit('/badge')->tree()) as $node) {
        expect($node)->not->toHaveKeys(['on_press', 'on_change'])
            ->and($node['props'])->not->toHaveKeys([
                'value', 'disabled', 'loading', 'error', 'required', 'helper',
            ]);
    }
});
