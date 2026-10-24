<?php

use Native\Mobile\Events\Async\AsyncTaskFinished;
use Native\Mobile\Support\AsyncTaskTransport;
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
    $screen = Native::visit('/components/confirmation-dialog');
    $nodes = confirmationDialogNodesByRef($screen->tree());

    expect($screen->tree()['props']['nav_title'] ?? null)->toBe('Firstlight Confirmation Dialog')
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
    $screen = Native::visit('/components/confirmation-dialog');

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

it('closes the timed confirmation on async completion without a user decision', function () {
    $bridge = Native::fakeBridge()->respondTo('AsyncTask.Dispatch', ['success' => true]);
    $screen = Native::visit('/components/confirmation-dialog');
    $screen->tap('showProgrammatic')->assertSet('programmaticVisible', true);
    $id = $bridge->callsTo('AsyncTask.Dispatch')[0]['params']['id'];

    $screen->emitNative(AsyncTaskFinished::class, ['id' => $id])
        ->assertSet('programmaticVisible', false)
        ->assertSet('confirmationCount', 0)->assertSet('dismissalCount', 0)
        ->assertSet('lastOutcome', 'Programmatic closure published no user outcome');

    $screen->tap('showProgrammatic')->tap('programmaticDialog')
        ->assertSet('confirmationCount', 1)->assertSet('dismissalCount', 0)
        ->assertSet('lastOutcome', 'Programmatic example confirmed');
    $oldId = $bridge->callsTo('AsyncTask.Dispatch')[1]['params']['id'];
    $screen->tap('showProgrammatic')
        ->emitNative(AsyncTaskFinished::class, ['id' => $oldId])
        ->assertSet('programmaticVisible', true)
        ->dismissSheet('programmaticDialog')
        ->assertSet('confirmationCount', 1)->assertSet('dismissalCount', 1)
        ->assertSet('lastOutcome', 'Programmatic example cancelled');

    foreach ($bridge->callsTo('AsyncTask.Dispatch') as $call) {
        AsyncTaskTransport::forgetPayload($call['params']['id']);
    }
});

it('keeps the confirmation usable when timed dispatch is unavailable', function () {
    Native::fakeBridge()->respondTo('AsyncTask.Dispatch', ['success' => false]);
    Native::visit('/components/confirmation-dialog')->tap('showProgrammatic')
        ->assertSet('programmaticVisible', true)
        ->assertSet('lastOutcome', 'Timed close unavailable; confirm or cancel normally')
        ->dismissSheet('programmaticDialog')->assertSet('dismissalCount', 1);
});
