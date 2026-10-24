<?php

use Native\Mobile\Testing\Native;

/** @return array<string, array<string, mixed>> */
function bottomSheetShowcaseNodesByRef(array $tree): array
{
    $nodes = [];

    if (($tree['type'] ?? null) === 'firstlight.bottom-sheet') {
        $nodes[$tree['ref']] = $tree;
    }

    foreach ($tree['children'] ?? [] as $child) {
        $nodes = [...$nodes, ...bottomSheetShowcaseNodesByRef($child)];
    }

    return $nodes;
}

it('publishes the complete Bottom Sheet catalogue through the native wire tree', function () {
    $screen = Native::visit('/components/bottom-sheet');
    $nodes = bottomSheetShowcaseNodesByRef($screen->tree());

    expect($screen->tree()['props']['nav_title'] ?? null)->toBe('Firstlight Bottom Sheet')
        ->and(array_keys($nodes))->toBe([
            'defaultSheet',
            'programmaticSheet',
        ])
        ->and($nodes['defaultSheet']['props'])->toMatchArray([
            'visible' => false,
            'a11y_label' => 'Filters',
        ])
        ->and($nodes['defaultSheet']['props'])->not->toHaveKey('detents');

    foreach ($nodes as $node) {
        expect($node['props']['on_dismiss'] ?? null)->toBeInt();
    }

    $screen->assertAccessible();
});

it('dispatches sheet dismissal and programmatic closure', function () {
    $screen = Native::visit('/components/bottom-sheet');

    $screen->tap('showDefault')
        ->assertSet('defaultVisible', true)
        ->dismissSheet('defaultSheet')
        ->assertSet('defaultVisible', false)
        ->assertSet('dismissalCount', 1)
        ->assertSet('lastOutcome', 'Filters sheet dismissed');

    $screen->tap('showProgrammatic')
        ->assertSet('programmaticVisible', true)
        ->tap('closeProgrammatic')
        ->assertSet('programmaticVisible', false)
        ->assertSet('dismissalCount', 1)
        ->assertSet('lastOutcome', 'Programmatic closure published no user outcome');

    $screen->tap('showProgrammatic')->dismissSheet('programmaticSheet')
        ->assertSet('programmaticVisible', false)
        ->assertSet('dismissalCount', 2)
        ->assertSet('lastOutcome', 'Programmatic example dismissed');
});
