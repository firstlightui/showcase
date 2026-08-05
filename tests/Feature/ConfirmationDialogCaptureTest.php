<?php

use Native\Mobile\Testing\Native;

/** @return array<string, mixed>|null */
function confirmationDialogCaptureNode(array $tree): ?array
{
    if (($tree['ref'] ?? null) === 'captureDialog') {
        return $tree;
    }

    foreach ($tree['children'] ?? [] as $child) {
        if (($found = confirmationDialogCaptureNode($child)) !== null) {
            return $found;
        }
    }

    return null;
}

it('publishes the stable Confirmation Dialog documentation capture fixture', function () {
    $screen = Native::visit('/captures/confirmation-dialog');
    $dialog = confirmationDialogCaptureNode($screen->tree());

    expect($screen->tree()['props']['title'] ?? null)->toBe('Firstlight Confirmation Dialog')
        ->and($screen->tree()['props']['back'] ?? null)->toBeFalse()
        ->and($dialog)->not->toBeNull()
        ->and($dialog['props'])->toMatchArray([
            'visible' => true,
            'title' => 'Delete appointment?',
            'message' => 'This removes the appointment and cannot be undone.',
            'confirm_label' => 'Delete appointment',
            'cancel_label' => 'Keep appointment',
            'tone' => 'destructive',
        ])
        ->and($dialog['on_press'] ?? null)->toBeInt()
        ->and($dialog['props']['on_dismiss'] ?? null)->toBeInt();

    $screen->assertAccessible();
});
