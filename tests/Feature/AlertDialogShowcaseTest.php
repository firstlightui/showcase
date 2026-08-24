<?php

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
    $screen = Native::visit('/alert-dialog');
    $nodes = alertDialogNodesByRef($screen->tree());

    expect($screen->tree()['props']['title'] ?? null)->toBe('Firstlight Alert Dialog')
        ->and(array_keys($nodes))->toBe([
            'defaultDialog',
            'customActionDialog',
            'longCopyDialog',
            'programmaticDialog',
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
    $screen = Native::visit('/alert-dialog');

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
