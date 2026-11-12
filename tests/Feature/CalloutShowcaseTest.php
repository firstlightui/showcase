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
    $screen = Native::visit('/components/callout');
    $nodes = calloutShowcaseNodes($screen->tree());

    expect($screen->tree()['props']['nav_title'] ?? null)->toBe('Firstlight Callout')
        ->and(array_column(array_column($nodes, 'props'), 'tone'))->toBe([
            'neutral', 'info', 'success', 'warning', 'danger', 'warning', 'info', 'info',
        ])
        ->and($nodes[5]['props'])->toMatchArray([
            'message' => 'Your changes have not been submitted.',
            'tone' => 'warning',
            'action_label' => 'Review changes',
            'a11y_label' => 'Submission warning: changes have not been submitted',
            'a11y_hint' => 'Review the form before continuing',
        ])
        ->and($nodes[5]['on_press'] ?? null)->toBeInt()
        ->and($nodes[7]['props']['message'])->toContain('multidisciplinary care plan');

    $screen->assertAccessible();
});

it('dispatches the optional action exactly once per press', function () {
    Native::visit('/components/callout')
        ->tap('actionCallout')
        ->assertSet('actionCount', 1)
        ->assertSet('lastAction', 'Review changes pressed');
});

it('replaces message and tone programmatically and removes the callout by omission', function () {
    $screen = Native::visit('/components/callout');
    $sync = fn () => array_values(array_filter(
        calloutShowcaseNodes($screen->tree()),
        fn (array $node) => in_array($node['props']['message'], [
            'Appointments are syncing in the background.',
            'All appointments are up to date.',
            'Appointments could not be synced.',
        ], true),
    ));

    expect($sync())->toHaveCount(1)
        ->and($sync()[0]['props'])->toMatchArray([
            'message' => 'Appointments are syncing in the background.',
            'tone' => 'info',
        ]);

    $screen->tap('Next sync status')->assertSet('syncStatus', 'synced');
    expect($sync()[0]['props'])->toMatchArray([
        'message' => 'All appointments are up to date.',
        'tone' => 'success',
    ])->and($sync()[0])->not->toHaveKey('on_press');

    $screen->tap('Next sync status')->assertSet('syncStatus', 'failed');
    expect($sync()[0]['props'])->toMatchArray([
        'message' => 'Appointments could not be synced.',
        'tone' => 'danger',
    ]);

    $screen->tap('Remove callout')
        ->assertSet('syncNoticeShown', false)
        ->assertSee('PHP accepted state: sync failed · callout removed');
    expect($sync())->toBeEmpty();

    $screen->tap('Next sync status')->assertSet('syncNoticeShown', true)->assertSet('syncStatus', 'syncing');
    expect($sync())->toHaveCount(1)
        ->and($screen->get('actionCount'))->toBe(0);

    $screen->assertAccessible();
});
