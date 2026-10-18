<?php

use Native\Mobile\Testing\Native;

/** @return array<string, array<string, mixed>> */
function modalShowcaseNodesByRef(array $tree): array
{
    $nodes = [];

    if (($tree['type'] ?? null) === 'firstlight.modal') {
        $nodes[$tree['ref']] = $tree;
    }

    foreach ($tree['children'] ?? [] as $child) {
        $nodes = [...$nodes, ...modalShowcaseNodesByRef($child)];
    }

    return $nodes;
}

it('publishes the complete Modal catalogue through the native wire tree', function () {
    $screen = Native::visit('/modal');
    $nodes = modalShowcaseNodesByRef($screen->tree());

    expect($screen->tree()['props']['title'] ?? null)->toBe('Firstlight Modal')
        ->and(array_keys($nodes))->toBe([
            'defaultModal',
            'lockedModal',
            'programmaticModal',
        ])
        ->and($nodes['defaultModal']['props'])->toMatchArray([
            'visible' => false,
            'a11y_label' => 'Account details',
        ])
        ->and($nodes['lockedModal']['props'])->toMatchArray([
            'visible' => false,
            'dismissible' => false,
            'a11y_label' => 'Required notice',
        ]);

    foreach ($nodes as $node) {
        expect($node['props']['on_dismiss'] ?? null)->toBeInt();
    }

    $screen->assertAccessible();
});

it('dispatches overlay dismissal and programmatic closure', function () {
    $screen = Native::visit('/modal');

    $screen->tap('showDefault')
        ->assertSet('defaultVisible', true)
        ->dismissSheet('defaultModal')
        ->assertSet('defaultVisible', false)
        ->assertSet('dismissalCount', 1)
        ->assertSet('lastOutcome', 'Account overlay dismissed');

    $screen->tap('showLocked')
        ->assertSet('lockedVisible', true)
        ->tap('acknowledgeLocked')
        ->assertSet('lockedVisible', false)
        ->assertSet('lastOutcome', 'Required notice acknowledged');

    $screen->tap('showProgrammatic')
        ->assertSet('programmaticVisible', true)
        ->tap('closeProgrammatic')
        ->assertSet('programmaticVisible', false)
        ->assertSet('dismissalCount', 1)
        ->assertSet('lastOutcome', 'Programmatic closure published no user outcome');

    $screen->tap('showProgrammatic')->dismissSheet('programmaticModal')
        ->assertSet('programmaticVisible', false)
        ->assertSet('dismissalCount', 2)
        ->assertSet('lastOutcome', 'Programmatic example dismissed');
});
