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
            'billSheet',
            'heightSheet',
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

it('publishes the long bill body separately from its pinned total and save action', function () {
    $screen = Native::visit('/components/bottom-sheet');
    $screen->tap('Open long bill')->assertSet('billVisible', true);
    $sheet = bottomSheetShowcaseNodesByRef($screen->tree())['billSheet'];

    expect($sheet['props'])->toMatchArray(['visible' => true, 'pinned_footer' => true])
        ->and($sheet['children'])->toHaveCount(2)
        ->and($sheet['children'][0]['children'])->toHaveCount(30)
        ->and($sheet['children'][1]['children'][0]['props']['text'])->toBe('Total: $1,350.00')
        ->and($sheet['children'][1]['children'][1]['props']['label'])->toBe('Save bill');

    $screen->tap('Save bill')->assertSet('billVisible', false)
        ->assertSet('dismissalCount', 0);
    $screen->tap('Open long bill')->dismissSheet('billSheet')->assertSet('billVisible', false);
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

it('publishes an a11y-hint and republishes sheet content height while open', function () {
    $screen = Native::visit('/components/bottom-sheet');
    $rows = fn (array $sheet) => count(array_filter(
        $sheet['children'][0]['children'],
        fn (array $child) => str_starts_with($child['props']['text'] ?? '', 'Note '),
    ));

    $sheet = bottomSheetShowcaseNodesByRef($screen->tree())['heightSheet'];
    expect($sheet['props'])->toMatchArray([
        'visible' => false,
        'a11y_label' => 'Appointment notes',
        'a11y_hint' => 'Add 8 rows grows this sheet while it stays open',
    ]);

    $screen->tap('showHeightShort')->assertSet('heightVisible', true)->assertSet('heightRows', 3);
    expect($rows(bottomSheetShowcaseNodesByRef($screen->tree())['heightSheet']))->toBe(3);

    $screen->tap('addHeightRows')->assertSet('heightVisible', true)->assertSet('heightRows', 11);
    $sheet = bottomSheetShowcaseNodesByRef($screen->tree())['heightSheet'];
    expect($sheet['props']['visible'])->toBeTrue()->and($rows($sheet))->toBe(11);

    $screen->tap('resetHeightRows')->assertSet('heightRows', 3)
        ->dismissSheet('heightSheet')
        ->assertSet('heightVisible', false)
        ->assertSet('dismissalCount', 1)
        ->assertSet('lastOutcome', 'Height sheet dismissed');

    $screen->tap('showHeightHalf')->assertSet('heightRows', 14);
    expect($rows(bottomSheetShowcaseNodesByRef($screen->tree())['heightSheet']))->toBe(14);

    $screen->dismissSheet('heightSheet')->tap('showHeightFull')->assertSet('heightRows', 40);
    expect($rows(bottomSheetShowcaseNodesByRef($screen->tree())['heightSheet']))->toBe(40);

    $screen->assertAccessible();
});
