<?php

use Native\Mobile\Testing\Native;

/** @return array<string, mixed>|null */
function modalCaptureNode(array $tree): ?array
{
    if (($tree['ref'] ?? null) === 'captureModal') {
        return $tree;
    }

    foreach ($tree['children'] ?? [] as $child) {
        if (($found = modalCaptureNode($child)) !== null) {
            return $found;
        }
    }

    return null;
}

it('publishes the stable Modal documentation capture fixture', function () {
    $screen = Native::visit('/captures/modal');
    $modal = modalCaptureNode($screen->tree());

    expect($screen->tree()['props']['title'] ?? null)->toBe('Firstlight Modal')
        ->and($screen->tree()['props']['back'] ?? null)->toBeFalse()
        ->and($modal)->not->toBeNull()
        ->and($modal['type'] ?? null)->toBe('firstlight.modal')
        ->and($modal['props'])->toMatchArray([
            'visible' => true,
            'a11y_label' => 'Account details',
        ])
        ->and($modal['props']['on_dismiss'] ?? null)->toBeInt();

    $screen->assertAccessible();
});
