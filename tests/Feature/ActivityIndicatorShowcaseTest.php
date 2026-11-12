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
    $screen = Native::visit('/components/activity-indicator');
    $nodes = activityIndicatorShowcaseNodes($screen->tree());

    expect($screen->tree()['props']['nav_title'] ?? null)->toBe('Firstlight Activity Indicator')
        ->and(array_column(array_column($nodes, 'props'), 'size'))->toBe([
            'sm',
            'md',
            'lg',
            'md',
            'sm',
        ])
        ->and(array_column(array_column($nodes, 'props'), 'a11y_label'))->toBe([
            'Loading compact appointment summary',
            'Loading appointment list',
            'Synchronising the complete historical appointment archive for offline access',
            'Loading appointments',
            'Syncing appointments',
        ])
        ->and($nodes[3]['props'])->not->toHaveKeys(['on_change', 'on_submit', 'on_press']);

    $screen->tap('Hide activity indicator')->assertSet('loading', false);
    expect(activityIndicatorShowcaseNodes($screen->tree()))->toHaveCount(4);

    $screen->tap('Show activity indicator')->assertSet('loading', true);
    expect(activityIndicatorShowcaseNodes($screen->tree()))->toHaveCount(5);

    $screen->assertAccessible();
});

it('changes the size and label of a mounted Activity Indicator from PHP', function () {
    $screen = Native::visit('/components/activity-indicator');

    $screen->assertSee('PHP accepted state: size sm · label "Syncing appointments" · updates 0');

    $screen->tap('Change size and label')
        ->assertSet('mountedSize', 'md')
        ->assertSet('mountedLabel', 'Syncing appointment reminders')
        ->assertSet('mountedUpdates', 1)
        ->assertSee('PHP accepted state: size md · label "Syncing appointment reminders" · updates 1');

    $nodes = activityIndicatorShowcaseNodes($screen->tree());

    expect($nodes)->toHaveCount(5)
        ->and($nodes[4]['props']['size'])->toBe('md')
        ->and($nodes[4]['props']['a11y_label'])->toBe('Syncing appointment reminders');

    $screen->tap('Change size and label');
    $nodes = activityIndicatorShowcaseNodes($screen->tree());

    expect($nodes[4]['props']['size'])->toBe('lg')
        ->and($nodes[4]['props']['a11y_label'])->toBe('Syncing appointments');

    $screen->assertAccessible();
});
