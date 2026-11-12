<?php

use Native\Mobile\Events\Async\AsyncTaskFinished;
use Native\Mobile\Support\AsyncTaskTransport;
use Native\Mobile\Testing\Native;

/** @return array<string, array<string, mixed>> */
function alertDialogNodesByRef(array $tree): array
{
    $nodes = [];

    if (($tree['type'] ?? null) === 'firstlight.alert-dialog') {
        $nodes[$tree['ref']] = $tree;
    }

    foreach ($tree['children'] ?? [] as $child) {
        $nodes = [...$nodes, ...alertDialogNodesByRef($child)];
    }

    return $nodes;
}

it('publishes the complete Alert Dialog catalogue through the native wire tree', function () {
    $screen = Native::visit('/components/alert-dialog');
    $nodes = alertDialogNodesByRef($screen->tree());

    expect($screen->tree()['props']['nav_title'] ?? null)->toBe('Firstlight Alert Dialog')
        ->and(array_keys($nodes))->toBe([
            'defaultDialog',
            'customActionDialog',
            'packageLabelDialog',
            'longCopyDialog',
            'programmaticDialog',
            'copyUpdateDialog',
        ])
        ->and($nodes['defaultDialog']['props'])->toMatchArray([
            'visible' => false,
            'title' => 'Changes saved',
            'message' => 'Your profile was updated.',
            'action_label' => 'OK',
        ])
        ->and($nodes['customActionDialog']['props'])->toMatchArray([
            'visible' => false,
            'title' => 'Download complete',
            'action_label' => 'Got it',
        ])
        ->and($nodes['longCopyDialog']['props']['message'])->toContain('audit history')
        ->and($nodes['defaultDialog'])->not->toHaveKey('on_press');

    foreach ($nodes as $node) {
        expect($node['props']['on_dismiss'] ?? null)->toBeInt();
    }

    $screen->assertAccessible();
});

it('dispatches acknowledgement repeated use and programmatic closure', function () {
    $screen = Native::visit('/components/alert-dialog');

    $screen->tap('showDefault')
        ->assertSet('defaultVisible', true)
        ->dismissSheet('defaultDialog')
        ->assertSet('defaultVisible', false)
        ->assertSet('acknowledgementCount', 1)
        ->assertSet('lastOutcome', 'Changes saved acknowledged')
        ->tap('showDefault')
        ->dismissSheet('defaultDialog')
        ->assertSet('acknowledgementCount', 2);

    $screen->tap('showCustomAction')
        ->assertSet('customActionVisible', true)
        ->dismissSheet('customActionDialog')
        ->assertSet('customActionVisible', false)
        ->assertSet('acknowledgementCount', 3)
        ->assertSet('lastOutcome', 'Got it acknowledged');

    $screen->tap('showProgrammatic')
        ->assertSet('programmaticVisible', true)
        ->call('closeProgrammatic')
        ->assertSet('programmaticVisible', false)
        ->assertSet('acknowledgementCount', 3)
        ->assertSet('lastOutcome', 'Programmatic closure published no user outcome');
});

it('closes the timed alert on async completion without an acknowledgement', function () {
    $bridge = Native::fakeBridge()->respondTo('AsyncTask.Dispatch', ['success' => true]);
    $screen = Native::visit('/components/alert-dialog');
    $screen->tap('showProgrammatic')->assertSet('programmaticVisible', true);
    $id = $bridge->callsTo('AsyncTask.Dispatch')[0]['params']['id'];

    $screen->emitNative(AsyncTaskFinished::class, ['id' => $id])
        ->assertSet('programmaticVisible', false)
        ->assertSet('acknowledgementCount', 0)
        ->assertSet('lastOutcome', 'Programmatic closure published no user outcome');

    $screen->tap('showProgrammatic')->dismissSheet('programmaticDialog')
        ->assertSet('acknowledgementCount', 1)
        ->assertSet('lastOutcome', 'Programmatic example acknowledged');
    $oldId = $bridge->callsTo('AsyncTask.Dispatch')[1]['params']['id'];
    $screen->tap('showProgrammatic')
        ->emitNative(AsyncTaskFinished::class, ['id' => $oldId])
        ->assertSet('programmaticVisible', true)
        ->assertSet('acknowledgementCount', 1);

    foreach ($bridge->callsTo('AsyncTask.Dispatch') as $call) {
        AsyncTaskTransport::forgetPayload($call['params']['id']);
    }
});

it('keeps the alert usable when timed dispatch is unavailable', function () {
    Native::fakeBridge()->respondTo('AsyncTask.Dispatch', ['success' => false]);
    Native::visit('/components/alert-dialog')->tap('showProgrammatic')
        ->assertSet('programmaticVisible', true)
        ->assertSet('lastOutcome', 'Timed close unavailable; acknowledge normally')
        ->dismissSheet('programmaticDialog')->assertSet('acknowledgementCount', 1);
});

it('publishes the package chrome OK when the action label is omitted', function () {
    $screen = Native::visit('/components/alert-dialog');
    $node = alertDialogNodesByRef($screen->tree())['packageLabelDialog'];

    expect($node['props'])->toMatchArray([
        'visible' => false,
        'title' => 'Reminder sent',
        'action_label' => 'OK',
    ])->and($node['props']['on_dismiss'] ?? null)->toBeInt();

    $screen->tap('showPackageLabel')
        ->assertSet('packageLabelVisible', true)
        ->dismissSheet('packageLabelDialog')
        ->assertSet('packageLabelVisible', false)
        ->assertSet('acknowledgementCount', 1)
        ->assertSet('lastOutcome', 'Package OK acknowledged');
});

it('republishes copy while open and after dismissal without reopening', function () {
    $bridge = Native::fakeBridge()->respondTo('AsyncTask.Dispatch', ['success' => true]);
    $screen = Native::visit('/components/alert-dialog');

    $screen->tap('showCopyUpdate')->assertSet('copyVisible', true)->assertSet('copyRevision', 1);
    $id = $bridge->callsTo('AsyncTask.Dispatch')[0]['params']['id'];

    $screen->emitNative(AsyncTaskFinished::class, ['id' => $id])
        ->assertSet('copyVisible', true)
        ->assertSet('copyRevision', 2)
        ->assertSet('acknowledgementCount', 0);

    $node = alertDialogNodesByRef($screen->tree())['copyUpdateDialog'];
    expect($node['props']['visible'])->toBeTrue()
        ->and($node['props']['message'])->toContain('revision 2');

    $screen->dismissSheet('copyUpdateDialog')
        ->assertSet('copyVisible', false)
        ->assertSet('acknowledgementCount', 1)
        ->assertSet('lastOutcome', 'Copy update acknowledged')
        ->tap('republishCopy')
        ->assertSet('copyRevision', 3)
        ->assertSet('copyVisible', false)
        ->assertSee('PHP state: copy alert closed · copy revision 3');

    $node = alertDialogNodesByRef($screen->tree())['copyUpdateDialog'];
    expect($node['props']['visible'])->toBeFalse()
        ->and($node['props']['message'])->toContain('revision 3');

    foreach ($bridge->callsTo('AsyncTask.Dispatch') as $call) {
        AsyncTaskTransport::forgetPayload($call['params']['id']);
    }
});
