<?php

use Native\Mobile\Testing\Native;

/** @return array<string, mixed>|null */
function alertDialogCaptureNode(array $tree): ?array
{
    if (($tree['ref'] ?? null) === 'captureDialog') {
        return $tree;
    }

    foreach ($tree['children'] ?? [] as $child) {
        if (($found = alertDialogCaptureNode($child)) !== null) {
            return $found;
        }
    }

    return null;
}

it('publishes the stable Alert Dialog documentation capture fixture', function () {
    $screen = Native::visit('/captures/alert-dialog');
    $dialog = alertDialogCaptureNode($screen->tree());

    expect($screen->tree()['props']['title'] ?? null)->toBe('Firstlight Alert Dialog')
        ->and($screen->tree()['props']['back'] ?? null)->toBeFalse()
        ->and($dialog)->not->toBeNull()
        ->and($dialog['type'] ?? null)->toBe('firstlight.alert-dialog')
        ->and($dialog['props'])->toMatchArray([
            'visible' => true,
            'title' => 'Changes saved',
            'message' => 'Your profile was updated.',
            'action_label' => 'OK',
        ])
        ->and($dialog)->not->toHaveKey('on_press')
        ->and($dialog['props']['on_dismiss'] ?? null)->toBeInt();

    $screen->assertAccessible();
});
