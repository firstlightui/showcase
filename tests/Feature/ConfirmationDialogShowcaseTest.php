<?php

use Native\Mobile\Testing\Native;

/** @return array<string, array<string, mixed>> */
function confirmationDialogNodesByRef(array $tree): array
{
    $nodes = [];

    if (($tree['type'] ?? null) === 'firstlight.confirmation-dialog') {
        $nodes[$tree['ref']] = $tree;
    }

    foreach ($tree['children'] ?? [] as $child) {
        $nodes = [...$nodes, ...confirmationDialogNodesByRef($child)];
    }

    return $nodes;
}

it('publishes the complete Confirmation Dialog catalogue through the native wire tree', function () {
    $screen = Native::visit('/confirmation-dialog');
    $nodes = confirmationDialogNodesByRef($screen->tree());

    expect($screen->tree()['props']['title'] ?? null)->toBe('Firstlight Confirmation Dialog')
        ->and(array_keys($nodes))->toBe([
            'defaultDialog',
            'destructiveDialog',
            'longCopyDialog',
            'programmaticDialog',
        ])
        ->and($nodes['defaultDialog']['props'])->toMatchArray([
            'visible' => false,
            'title' => 'Apply these changes?',
            'confirm_label' => 'Apply changes',
            'cancel_label' => 'Keep editing',
            'tone' => 'default',
        ])
        ->and($nodes['destructiveDialog']['props'])->toMatchArray([
            'visible' => false,
            'title' => 'Delete appointment?',
            'confirm_label' => 'Delete appointment',
            'cancel_label' => 'Keep appointment',
            'tone' => 'destructive',
        ])
        ->and($nodes['longCopyDialog']['props']['message'])->toContain('clinical audit history');

    foreach ($nodes as $node) {
        expect($node['on_press'] ?? null)->toBeInt()
            ->and($node['props']['on_dismiss'] ?? null)->toBeInt();
    }

    $screen->assertAccessible();
});

it('dispatches confirmation dismissal repeated use and programmatic closure', function () {
    $screen = Native::visit('/confirmation-dialog');

    $screen->tap('showDefault')
        ->assertSet('defaultVisible', true)
        ->tap('defaultDialog')
        ->assertSet('defaultVisible', false)
        ->assertSet('confirmationCount', 1)
        ->assertSet('lastOutcome', 'Default action confirmed')
        ->tap('showDefault')
        ->tap('defaultDialog')
        ->assertSet('confirmationCount', 2);

    $screen->tap('showDestructive')
        ->assertSet('destructiveVisible', true)
        ->dismissSheet('destructiveDialog')
        ->assertSet('destructiveVisible', false)
        ->assertSet('dismissalCount', 1)
        ->assertSet('lastOutcome', 'Destructive action cancelled');

    $screen->tap('showProgrammatic')
        ->assertSet('programmaticVisible', true)
        ->call('closeProgrammatic')
        ->assertSet('programmaticVisible', false)
        ->assertSet('confirmationCount', 2)
        ->assertSet('dismissalCount', 1)
        ->assertSet('lastOutcome', 'Programmatic closure published no user outcome');
});
