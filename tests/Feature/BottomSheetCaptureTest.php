<?php

use Native\Mobile\Testing\Native;

/** @return array<string, mixed>|null */
function bottomSheetCaptureNode(array $tree): ?array
{
    if (($tree['ref'] ?? null) === 'captureSheet') {
        return $tree;
    }

    foreach ($tree['children'] ?? [] as $child) {
        if (($found = bottomSheetCaptureNode($child)) !== null) {
            return $found;
        }
    }

    return null;
}

it('publishes the stable Bottom Sheet documentation capture fixture', function () {
    $screen = Native::visit('/captures/bottom-sheet');
    $sheet = bottomSheetCaptureNode($screen->tree());

    expect($screen->tree()['props']['title'] ?? null)->toBe('Firstlight Bottom Sheet')
        ->and($screen->tree()['props']['back'] ?? null)->toBeFalse()
        ->and($sheet)->not->toBeNull()
        ->and($sheet['type'] ?? null)->toBe('firstlight.bottom-sheet')
        ->and($sheet['props'])->toMatchArray([
            'visible' => true,
            'a11y_label' => 'Filters',
        ])
        ->and($sheet['props']['on_dismiss'] ?? null)->toBeInt()
        ->and($sheet['props'])->not->toHaveKey('detents');

    $screen->assertAccessible();
});
