<?php

use Native\Mobile\Testing\Native;

/** @return list<array<string, mixed>> */
function calloutShowcaseNodes(array $tree): array
{
    $nodes = ($tree['type'] ?? null) === 'firstlight.callout' ? [$tree] : [];

    foreach ($tree['children'] ?? [] as $child) {
        $nodes = [...$nodes, ...calloutShowcaseNodes($child)];
    }

    return $nodes;
}

it('publishes every Callout tone, an action, accessibility metadata, and long copy', function () {
    $screen = Native::visit('/callout');
    $nodes = calloutShowcaseNodes($screen->tree());

    expect($screen->tree()['props']['title'] ?? null)->toBe('Firstlight Callout')
        ->and(array_column(array_column($nodes, 'props'), 'tone'))->toBe([
            'neutral', 'info', 'success', 'warning', 'danger', 'warning', 'info',
        ])
        ->and($nodes[5]['props'])->toMatchArray([
            'message' => 'Your changes have not been submitted.',
            'tone' => 'warning',
            'action_label' => 'Review changes',
            'a11y_label' => 'Submission warning: changes have not been submitted',
            'a11y_hint' => 'Review the form before continuing',
        ])
        ->and($nodes[5]['on_press'] ?? null)->toBeInt()
        ->and($nodes[6]['props']['message'])->toContain('multidisciplinary care plan');

    $screen->assertAccessible();
});

it('dispatches the optional action exactly once per press', function () {
    Native::visit('/callout')
        ->tap('actionCallout')
        ->assertSet('actionCount', 1)
        ->assertSet('lastAction', 'Review changes pressed');
});
