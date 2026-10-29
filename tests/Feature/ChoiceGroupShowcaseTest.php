<?php

use Native\Mobile\Testing\Native;

/** @return array<string, array<string, mixed>> */
function firstlightChoiceGroupNodesByLabel(array $tree): array
{
    $nodes = [];

    if (($tree['type'] ?? null) === 'firstlight.choice-group') {
        $nodes[$tree['props']['label']] = $tree;
    }

    foreach ($tree['children'] ?? [] as $child) {
        $nodes = [...$nodes, ...firstlightChoiceGroupNodesByLabel($child)];
    }

    return $nodes;
}

it('publishes the complete Choice Group catalogue through the native tree', function () {
    $screen = Native::visit('/components/choice-group');
    $nodes = firstlightChoiceGroupNodesByLabel($screen->tree());

    expect($screen->tree()['props']['nav_title'] ?? null)->toBe('Firstlight Choice Group')
        ->and(array_keys($nodes))->toBe([
            'Priority',
            'Notifications',
            'Triage level',
            'Locked priority',
            'Priority with error',
            'Disabled priority',
            'Favourites',
            'Next step',
        ])
        ->and($nodes['Priority']['props']['selected_values'])->toBe(['routine'])
        ->and($nodes['Priority']['props']['option_callbacks'][0])->toBe('0')
        ->and($nodes['Notifications']['props']['selected_values'])->toBe(['email', 'push'])
        ->and($nodes['Notifications']['props']['multiple'])->toBeTrue()
        ->and($nodes['Triage level']['props']['value_type'])->toBe('integer')
        ->and($nodes['Triage level']['props']['option_enabled'])->toBe(['1', '0', '1'])
        ->and($nodes['Priority with error']['props']['error'])->toBe('Priority is required.')
        ->and($nodes['Disabled priority']['props']['disabled'])->toBeTrue()
        ->and($nodes['Favourites']['props'])->toMatchArray([
            'variant' => 'tiles', 'multiple' => true, 'selected_values' => ['consult'],
            'option_prefixes' => ['110', '116', '119'], 'option_supporting' => ['$90.00', '$45.00', '$65.00'],
            'option_enabled' => ['1', '1', '0'],
        ]);

    $screen->assertAccessible();
});

it('dispatches complete single and multiple proposals through PRESS callbacks', function () {
    $screen = Native::visit('/components/choice-group');

    $screen->press("__syncProperty('errorPriority', \"urgent\")")
        ->assertSet('errorPriority', 'urgent')
        ->assertSee('PHP accepted state: urgent')
        ->press("__syncProperty('nextStep', \"escalate\")")
        ->assertSet('nextStep', 'escalate')
        ->assertSee('PHP accepted state: escalate');

    $screen->press("__syncProperty('priority', \"urgent\")")
        ->assertSet('priority', 'urgent');

    $screen->press("__syncProperty('notifications', [\"push\"])")
        ->assertSet('notifications', ['push']);

    $screen->press("__syncProperty('favourites', [\"consult\",\"review\"])")
        ->assertSet('favourites', ['consult', 'review']);

    $nodes = firstlightChoiceGroupNodesByLabel($screen->tree());
    expect($nodes['Priority with error']['props']['selected_values'])->toBe(['urgent'])
        ->and($nodes['Next step']['props']['selected_values'])->toBe(['escalate'])
        ->and($nodes['Favourites']['props']['selected_values'])->toBe(['consult', 'review']);
    $screen->tap('Reset choices')->assertSee('PHP accepted state: email, push');
});

it('publishes an unchanged authoritative choice after rejection', function () {
    $screen = Native::visit('/components/choice-group');

    $screen->press('rejectPriority("urgent")')
        ->assertSet('rejectedPriority', 'routine')
        ->assertSet('rejectedAttempts', 1);

    $screen->assertSee('Rejected attempts: 1');

    expect(firstlightChoiceGroupNodesByLabel($screen->tree())['Locked priority']['props']['selected_values'])
        ->toBe(['routine']);
});
