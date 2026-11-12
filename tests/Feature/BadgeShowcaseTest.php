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
    $screen = Native::visit('/components/badge');
    $nodes = badgeShowcaseNodes($screen->tree());

    expect($screen->tree()['props']['nav_title'] ?? null)->toBe('Firstlight Badge')
        ->and($nodes)->toHaveCount(14)
        ->and(array_column(array_slice(array_column($nodes, 'props'), 0, 5), 'label'))->toBe([
            '', '1', '9', '99', '99+',
        ])
        ->and(array_column(array_slice(array_column($nodes, 'props'), 5, 5), 'tone'))->toBe([
            'neutral', 'info', 'success', 'warning', 'danger',
        ])
        ->and($nodes[13]['props']['a11y_label'])->toBe('Prescription ready')
        ->and($nodes[13]['props']['a11y_hint'])->toBe('Open the prescription to review it');

    $screen->assertAccessible();
});

it('publishes no callback or mutable state props', function () {
    foreach (badgeShowcaseNodes(Native::visit('/components/badge')->tree()) as $node) {
        expect($node)->not->toHaveKeys(['on_press', 'on_change'])
            ->and($node['props'])->not->toHaveKeys([
                'value', 'disabled', 'loading', 'error', 'required', 'helper',
            ]);
    }
});

it('replaces count, label and tone through later publications without callbacks', function () {
    $screen = Native::visit('/components/badge');
    $props = fn () => array_column(array_slice(badgeShowcaseNodes($screen->tree()), 10, 2), 'props');

    expect($props()[0])->toMatchArray(['label' => '3', 'tone' => 'danger', 'a11y_label' => '3 unread messages'])
        ->and($props()[1])->toMatchArray(['label' => 'Wait', 'tone' => 'warning']);

    $screen->tap('Add 49 unread')->tap('Add 49 unread')->assertSet('unreadCount', 101);
    expect($props()[0])->toMatchArray(['label' => '99+', 'a11y_label' => '101 unread messages']);

    $screen->tap('Clear unread')->assertSet('unreadCount', 0)
        ->assertSee('PHP accepted state: unread 0 · status Wait');
    expect($props()[0]['label'])->toBe('');

    $screen->tap('Toggle status label')->assertSet('prescriptionReady', true);
    expect($props()[1])->toMatchArray(['label' => 'Ready', 'tone' => 'success'])
        ->and($props()[1])->not->toHaveKey('warning_color');
});

it('refreshes custom warning colours after Theme::merge republishes the tree', function () {
    $screen = Native::visit('/components/badge');
    $pending = fn () => array_values(array_filter(
        badgeShowcaseNodes($screen->tree()),
        fn (array $node) => ($node['props']['label'] ?? null) === 'Pending',
    ))[0]['props'];

    expect($pending())->toMatchArray([
        'tone' => 'warning',
        'warning_color' => '#B45309',
        'on_warning_color' => '#FFFFFF',
        'dark_warning_color' => '#FBBF24',
        'dark_on_warning_color' => '#1C1917',
    ]);

    $screen->tap('Apply custom warning colours')->assertSet('customWarning', true);
    expect($pending())->toMatchArray([
        'warning_color' => '#7C2D12',
        'on_warning_color' => '#FFFFFF',
        'dark_warning_color' => '#FDBA74',
        'dark_on_warning_color' => '#1C1917',
    ]);

    $screen->tap('Restore default warning colours')->assertSet('customWarning', false);
    expect($pending())->toMatchArray([
        'warning_color' => '#B45309',
        'dark_warning_color' => '#FBBF24',
    ]);

    $screen->assertAccessible();
});
