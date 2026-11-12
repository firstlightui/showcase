<?php

use Native\Mobile\Testing\Native;

/** @return list<array<string, mixed>> */
function metricShowcaseNodes(array $tree): array
{
    $nodes = ($tree['type'] ?? null) === 'firstlight.metric' ? [$tree] : [];

    foreach ($tree['children'] ?? [] as $child) {
        $nodes = [...$nodes, ...metricShowcaseNodes($child)];
    }

    return $nodes;
}

it('publishes every Metric tone, detail, accessibility override and long value', function () {
    $screen = Native::visit('/components/metric');
    $nodes = metricShowcaseNodes($screen->tree());

    expect($screen->tree()['props']['nav_title'] ?? null)->toBe('Firstlight Metric')
        ->and($nodes)->toHaveCount(6)
        ->and(array_column(array_column($nodes, 'props'), 'label'))->toBe([
            'Events', 'Users', 'Response', 'Pending', 'Failures', 'Waiting',
        ])
        ->and(array_column(array_slice(array_column($nodes, 'props'), 0, 5), 'tone'))->toBe([
            'neutral', 'info', 'success', 'warning', 'danger',
        ])
        ->and($nodes[1]['props']['value'])->toBe('0')
        ->and($nodes[1]['props']['a11y_label'])->toBe('No affected users')
        ->and($nodes[2]['props']['value'])->toBe('1,234.50 milliseconds')
        ->and($nodes[3]['props']['detail'] ?? '')->toBe('');

    foreach ($nodes as $node) {
        expect($node)->not->toHaveKey('on_press')
            ->and($node['props'])->not->toHaveKeys(['on_press', 'on_change', 'disabled', 'loading', 'error']);
    }

    $screen->assertAccessible();
});

it('republishes a metric value, detail and tone from PHP with an a11y-hint', function () {
    $screen = Native::visit('/components/metric');
    $waiting = fn () => metricShowcaseNodes($screen->tree())[5]['props'];

    expect($waiting())->toMatchArray([
        'label' => 'Waiting',
        'value' => '4',
        'detail' => 'patients in the queue',
        'tone' => 'warning',
        'a11y_hint' => 'Updates when a patient checks in or is seen',
    ]);

    $screen->tap('Check in a patient')->tap('Check in a patient')->assertSet('waiting', 6)
        ->assertSee('PHP accepted state: 6 waiting · tone danger');
    expect($waiting())->toMatchArray(['value' => '6', 'tone' => 'danger']);

    $screen->tap('Clear the queue')->assertSet('waiting', 0);
    expect($waiting())->toMatchArray(['value' => '0', 'tone' => 'success'])
        ->and($waiting()['a11y_label'])->toContain('Waiting: 0');

    $screen->tap('Check in a patient');
    expect($waiting())->toMatchArray(['value' => '1', 'detail' => 'patient in the queue', 'tone' => 'warning']);

    $screen->assertAccessible();
});
