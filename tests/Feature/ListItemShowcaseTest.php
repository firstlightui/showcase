<?php

use Native\Mobile\Testing\Native;

/** @return list<array<string, mixed>> */
function listItemShowcaseNodes(array $tree): array
{
    $nodes = ($tree['type'] ?? null) === 'firstlight.list-item' ? [$tree] : [];

    foreach ($tree['children'] ?? [] as $child) {
        $nodes = [...$nodes, ...listItemShowcaseNodes($child)];
    }

    return $nodes;
}

it('publishes every List Item content and state fixture through the native wire tree', function () {
    $screen = Native::visit('/components/list-item');
    $nodes = listItemShowcaseNodes($screen->tree());

    expect($screen->tree()['props']['nav_title'] ?? null)->toBe('Firstlight List Item')
        ->and($nodes)->toHaveCount(13)
        ->and(array_column(array_column($nodes, 'props'), 'headline'))->toBe([
            'Account',
            'Billing',
            'Profile',
            'Team',
            'Notifications',
            'Message',
            'Grouped message',
            'Disabled message',
            'Message from Alex',
            'Managed account',
            'Alex Morgan',
            'Sam Lee',
            'Taylor Green',
        ])
        ->and($nodes[0]['props'])->not->toHaveKeys(['supporting', 'leading_type', 'trailing_type'])
        ->and($nodes[1]['props'])->toMatchArray([
            'supporting' => 'Invoices and payment methods',
            'trailing_type' => 'text',
            'trailing_value' => 'Open',
        ])
        ->and($nodes[2]['props'])->toMatchArray([
            'leading_type' => 'monogram',
            'leading_value' => 'AJ',
        ])
        ->and($nodes[3]['props'])->toMatchArray([
            'leading_type' => 'monogram',
            'leading_value' => 'FL',
            'trailing_type' => 'text',
        ])
        ->and($nodes[4]['props'])->toMatchArray([
            'leading_type' => 'icon',
            'trailing_type' => 'icon',
            'a11y_hint' => 'Records a Notifications press; does not navigate',
        ])
        ->and($nodes[7]['props']['disabled'])->toBeTrue()
        ->and($nodes[9]['props']['disabled'])->toBeTrue()
        ->and($nodes[10]['props'])->toMatchArray([
            'leading_type' => 'label', 'leading_value' => '4B-12',
            'trailing_type' => 'status', 'trailing_value' => 'Billed today', 'trailing_status_tone' => 'success',
        ])
        ->and($nodes[11]['props'])->toMatchArray([
            'state' => 'provisional', 'state_label' => 'Provisional admission waiting to sync',
        ])
        ->and($nodes[12]['props'])->toMatchArray([
            'state' => 'pending-removal', 'state_label' => 'Discharge waiting to sync',
        ]);

    foreach ($nodes as $node) {
        expect($node['on_press'] ?? null)->toBeInt();
    }

    $screen->assertAccessible();
});

it('dispatches ordinary row presses and publishes the PHP result', function () {
    $screen = Native::visit('/components/list-item');
    $screen->tap('Account');
    $screen->tap('Team');

    expect($screen->get('pressCount'))->toBe(2)
        ->and($screen->get('lastPressed'))->toBe('Team');
    $screen->assertSee('Recorded presses: 2');
    $screen->assertSee('Last row: Team');

    $screen->tap('Notifications')->assertSet('pressCount', 3)
        ->assertSet('lastPressed', 'Notifications')
        ->assertSee('Last row: Notifications');
});

it('dispatches independent swipe callbacks with stable keys without removing rows', function () {
    $screen = Native::visit('/components/list-item');
    $nodes = listItemShowcaseNodes($screen->tree());
    $trailing = json_decode($nodes[5]['props']['firstlight_trailing_actions_json'], true, flags: JSON_THROW_ON_ERROR);

    expect(array_column($trailing, 'label'))->toBe(['Delete', 'Flag']);
    $screen->press("recordSwipe('Archive',42)")
        ->assertSet('swipeCount', 1)->assertSet('lastSwipe', 'Archive #42');
    $screen->press("recordSwipe('Delete',42)")
        ->assertSet('swipeCount', 2)->assertSet('lastSwipe', 'Delete #42');
    $screen->press("recordSwipe('Flag',73)")
        ->assertSet('swipeCount', 3)->assertSet('lastSwipe', 'Flag #73')
        ->assertSet('pressCount', 0);
    expect(listItemShowcaseNodes($screen->tree()))->toHaveCount(13);

    $screen->tap('Toggle swipe rows');
    $nodes = listItemShowcaseNodes($screen->tree());
    expect($nodes[5]['props']['disabled'])->toBeTrue()
        ->and($nodes[6]['props']['disabled'])->toBeTrue();
});

it('dispatches context menu callbacks and republishes item and row availability', function () {
    $screen = Native::visit('/components/list-item');
    $menu = fn () => json_decode(listItemShowcaseNodes($screen->tree())[8]['props']['firstlight_context_menu_json'] ?? '[]', true, flags: JSON_THROW_ON_ERROR);

    expect(array_column($menu(), 'label'))->toBe(['Reply', 'Forward', 'Pin', 'Delete'])
        ->and(array_column($menu(), 'disabled'))->toBe([false, true, false, false])
        ->and($menu()[3]['role'])->toBe('destructive');

    $screen->press("recordMenu('Reply',42)")
        ->assertSet('menuCount', 1)->assertSet('lastMenu', 'Reply #42');
    $screen->press("recordMenu('Delete',42)")
        ->assertSet('menuCount', 2)->assertSet('lastMenu', 'Delete #42')
        ->assertSet('pressCount', 0)
        ->assertSee('Last menu action: Delete #42');
    expect(listItemShowcaseNodes($screen->tree()))->toHaveCount(13);

    $screen->tap('Toggle Forward');
    expect($menu()[1]['disabled'])->toBeFalse()->and($menu()[1]['cb'])->toBeGreaterThan(0);

    $screen->tap('Toggle menu row');
    expect(listItemShowcaseNodes($screen->tree())[8]['props']['disabled'])->toBeTrue();
});
