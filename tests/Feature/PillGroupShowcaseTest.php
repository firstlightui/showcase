<?php

use Native\Mobile\Testing\Native;

/** @return array<string, array<string, mixed>> */
function firstlightPillGroupNodesByLabel(array $tree): array
{
    $nodes = [];

    if (($tree['type'] ?? null) === 'firstlight.pill-group') {
        $nodes[$tree['props']['label']] = $tree;
    }

    foreach ($tree['children'] ?? [] as $child) {
        $nodes = [...$nodes, ...firstlightPillGroupNodesByLabel($child)];
    }

    return $nodes;
}

it('publishes the complete Pill Group catalogue through the native tree', function () {
    $screen = Native::visit('/pill-group');
    $nodes = firstlightPillGroupNodesByLabel($screen->tree());

    expect($screen->tree()['props']['title'] ?? null)->toBe('Firstlight Pill Group')
        ->and(array_keys($nodes))->toBe([
            'Queue',
            'Queues',
            'Priority',
            'Locked queue',
            'Queue with error',
            'Disabled queue',
            'Workflow status',
        ])
        ->and($nodes['Queue']['props']['selected_values'])->toBe(['mine'])
        ->and($nodes['Queues']['props']['selected_values'])->toBe(['mine', 'urgent'])
        ->and($nodes['Queues']['props']['multiple'])->toBeTrue()
        ->and($nodes['Priority']['props']['value_type'])->toBe('integer')
        ->and($nodes['Priority']['props']['option_enabled'])->toBe(['1', '0', '1'])
        ->and($nodes['Queue with error']['props']['error'])->toBe('Choose at least one queue.')
        ->and($nodes['Disabled queue']['props']['disabled'])->toBeTrue();

    $screen->assertAccessible();
});

it('dispatches complete single and multiple proposals through PRESS callbacks', function () {
    $screen = Native::visit('/pill-group');

    $screen->press("__syncProperty('queue', \"all\")")
        ->assertSet('queue', 'all');

    $screen->press("__syncProperty('queues', [\"urgent\"])")
        ->assertSet('queues', ['urgent']);
});

it('publishes the unchanged authoritative selection after rejection', function () {
    $screen = Native::visit('/pill-group');

    $screen->press('rejectQueue("all")')
        ->assertSet('rejectedQueue', 'mine')
        ->assertSet('rejectedAttempts', 1);

    expect(firstlightPillGroupNodesByLabel($screen->tree())['Locked queue']['props']['selected_values'])
        ->toBe(['mine']);
});
