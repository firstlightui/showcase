<?php

use Native\Mobile\Testing\Native;

/** @return list<array<string, mixed>> */
function activityIndicatorShowcaseNodes(array $tree): array
{
    $nodes = ($tree['type'] ?? null) === 'firstlight.activity-indicator' ? [$tree] : [];

    foreach ($tree['children'] ?? [] as $child) {
        $nodes = [...$nodes, ...activityIndicatorShowcaseNodes($child)];
    }

    return $nodes;
}

it('publishes all Activity Indicator sizes and conditional presence', function () {
    $screen = Native::visit('/activity-indicator');
    $nodes = activityIndicatorShowcaseNodes($screen->tree());

    expect($screen->tree()['props']['title'] ?? null)->toBe('Firstlight Activity Indicator')
        ->and(array_column(array_column($nodes, 'props'), 'size'))->toBe([
            'sm',
            'md',
            'lg',
            'md',
        ])
        ->and(array_column(array_column($nodes, 'props'), 'a11y_label'))->toBe([
            'Loading compact appointment summary',
            'Loading appointment list',
            'Synchronising the complete historical appointment archive for offline access',
            'Loading appointments',
        ])
        ->and($nodes[3]['props'])->not->toHaveKeys(['on_change', 'on_submit', 'on_press']);

    $screen->tap('Hide activity indicator')->assertSet('loading', false);
    expect(activityIndicatorShowcaseNodes($screen->tree()))->toHaveCount(3);

    $screen->tap('Show activity indicator')->assertSet('loading', true);
    expect(activityIndicatorShowcaseNodes($screen->tree()))->toHaveCount(4);

    $screen->assertAccessible();
});
