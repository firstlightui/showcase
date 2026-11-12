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
        ->and($nodes)->toHaveCount(23)
        ->and(array_column(array_column($nodes, 'props'), 'headline'))->toBe([
            'Account',
            'Billing',
            'Profile',
            'Team',
            'Notifications',
            'Upload failed',
            'Sync delayed',
            'Referral sent',
            'Custom colours',
            'Dr A. Chen',
            'Referral pending',
            'Message',
            'Message with menu',
            'Grouped message',
            'Disabled message',
            'Message from Alex',
            'Footer-only row',
            'Bare section row',
            'Ward round row',
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
        ->and($nodes[14]['props']['disabled'])->toBeTrue()
        ->and($nodes[19]['props']['disabled'])->toBeTrue()
        ->and($nodes[20]['props'])->toMatchArray([
            'leading_type' => 'label', 'leading_value' => '4B-12',
            'trailing_type' => 'status', 'trailing_value' => 'Billed today', 'trailing_status_tone' => 'success',
        ])
        ->and($nodes[21]['props'])->toMatchArray([
            'state' => 'provisional', 'state_label' => 'Provisional admission waiting to sync',
        ])
        ->and($nodes[22]['props'])->toMatchArray([
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
    $trailing = json_decode($nodes[11]['props']['firstlight_trailing_actions_json'], true, flags: JSON_THROW_ON_ERROR);

    expect(array_column($trailing, 'label'))->toBe(['Delete', 'Flag']);
    $screen->press("recordSwipe('Archive',42)")
        ->assertSet('swipeCount', 1)->assertSet('lastSwipe', 'Archive #42');
    $screen->press("recordSwipe('Delete',42)")
        ->assertSet('swipeCount', 2)->assertSet('lastSwipe', 'Delete #42');
    $screen->press("recordSwipe('Flag',73)")
        ->assertSet('swipeCount', 3)->assertSet('lastSwipe', 'Flag #73')
        ->assertSet('pressCount', 0);
    expect(listItemShowcaseNodes($screen->tree()))->toHaveCount(23);

    $screen->tap('Toggle swipe rows');
    $nodes = listItemShowcaseNodes($screen->tree());
    expect($nodes[11]['props']['disabled'])->toBeTrue()
        ->and($nodes[12]['props']['disabled'])->toBeTrue()
        ->and($nodes[13]['props']['disabled'])->toBeTrue();
});

it('dispatches context menu callbacks and republishes item and row availability', function () {
    $screen = Native::visit('/components/list-item');
    $menu = fn () => json_decode(listItemShowcaseNodes($screen->tree())[15]['props']['firstlight_context_menu_json'] ?? '[]', true, flags: JSON_THROW_ON_ERROR);

    expect(array_column($menu(), 'label'))->toBe(['Reply', 'Forward', 'Pin', 'Delete'])
        ->and(array_column($menu(), 'disabled'))->toBe([false, true, false, false])
        ->and($menu()[3]['role'])->toBe('destructive');

    $screen->press("recordMenu('Reply',42)")
        ->assertSet('menuCount', 1)->assertSet('lastMenu', 'Reply #42');
    $screen->press("recordMenu('Delete',42)")
        ->assertSet('menuCount', 2)->assertSet('lastMenu', 'Delete #42')
        ->assertSet('pressCount', 0)
        ->assertSee('Last menu action: Delete #42');
    expect(listItemShowcaseNodes($screen->tree()))->toHaveCount(23);

    $screen->tap('Toggle Forward');
    expect($menu()[1]['disabled'])->toBeFalse()->and($menu()[1]['cb'])->toBeGreaterThan(0);

    $screen->tap('Toggle menu row');
    expect(listItemShowcaseNodes($screen->tree())[15]['props']['disabled'])->toBeTrue();
});

/** @return array<string, mixed> */
function listItemShowcaseByHeadline(array $tree, string $headline): array
{
    foreach (listItemShowcaseNodes($tree) as $node) {
        if (($node['props']['headline'] ?? null) === $headline) {
            return $node;
        }
    }

    throw new RuntimeException("No list item headed [{$headline}].");
}

/** @return list<array<string, mixed>> */
function listItemShowcaseSections(array $tree): array
{
    $nodes = ($tree['type'] ?? null) === 'list_section' ? [$tree] : [];

    foreach ($tree['children'] ?? [] as $child) {
        $nodes = [...$nodes, ...listItemShowcaseSections($child)];
    }

    return $nodes;
}

it('publishes semantic icon tones and explicit icon colour overrides', function () {
    $tree = Native::visit('/components/list-item')->tree();

    expect(listItemShowcaseByHeadline($tree, 'Upload failed')['props'])->toMatchArray([
        'leading_value' => 'error',
        'leading_icon_tone' => 'danger',
    ])
        ->and(listItemShowcaseByHeadline($tree, 'Sync delayed')['props']['leading_icon_tone'])->toBe('warning')
        ->and(listItemShowcaseByHeadline($tree, 'Referral sent')['props'])->toMatchArray([
            'leading_icon_tone' => 'neutral',
            'trailing_type' => 'icon',
            'trailing_icon_tone' => 'success',
        ])
        ->and(listItemShowcaseByHeadline($tree, 'Custom colours')['props'])->toMatchArray([
            'leading_icon_tone' => 'info',
            'leading_icon_color' => '#7C3AED',
            'trailing_icon_tone' => 'danger',
            'trailing_icon_color' => '#0F766E',
        ]);
});

it('publishes an a11y-label and republishes headline and supporting text without a press', function () {
    $screen = Native::visit('/components/list-item');

    expect(listItemShowcaseByHeadline($screen->tree(), 'Dr A. Chen')['props'])->toMatchArray([
        'supporting' => 'Cardiology · Rm 4',
        'a11y_label' => 'Doctor Alice Chen, cardiology, room 4',
    ])
        ->and(listItemShowcaseByHeadline($screen->tree(), 'Referral pending')['props']['supporting'])
        ->toBe('Waiting for the cardiology clinic');

    $screen->tap('Toggle referral')->assertSet('referralAccepted', true)->assertSet('pressCount', 0);
    expect(listItemShowcaseByHeadline($screen->tree(), 'Referral accepted')['props']['supporting'])
        ->toBe('Cardiology booked for 14 March');

    $screen->tap('Referral accepted')->assertSet('pressCount', 1)->assertSet('lastPressed', 'Referral');
    $screen->tap('Toggle referral');
    expect(listItemShowcaseByHeadline($screen->tree(), 'Referral pending')['props']['headline'])->toBe('Referral pending');

    $screen->assertAccessible();
});

it('publishes swipe actions and a context menu on the same row', function () {
    $screen = Native::visit('/components/list-item');
    $props = listItemShowcaseByHeadline($screen->tree(), 'Message with menu')['props'];
    $actions = json_decode($props['firstlight_trailing_actions_json'], true, flags: JSON_THROW_ON_ERROR);
    $menu = json_decode($props['firstlight_context_menu_json'], true, flags: JSON_THROW_ON_ERROR);

    expect(array_column($actions, 'label'))->toBe(['Archive'])
        ->and(array_column($menu, 'label'))->toBe(['Reply', 'Pin']);

    $screen->press("recordSwipe('Archive',55)")
        ->assertSet('swipeCount', 1)->assertSet('lastSwipe', 'Archive #55')
        ->press("recordMenu('Pin',55)")
        ->assertSet('menuCount', 1)->assertSet('lastMenu', 'Pin #55')
        ->assertSet('pressCount', 0);

    $screen->tap('Toggle swipe rows');
    expect(listItemShowcaseByHeadline($screen->tree(), 'Message with menu')['props']['disabled'])->toBeTrue();
});

it('publishes footer-only, bare and programmatically renamed List Sections', function () {
    $screen = Native::visit('/components/list-item');
    $sections = fn () => array_slice(listItemShowcaseSections($screen->tree()), 1);

    expect($sections())->toHaveCount(3)
        ->and($sections()[0]['props'])->toBe(['footer' => 'Footer-only section: no header is authored.'])
        ->and($sections()[1]['props'] ?? [])->toBe([])
        ->and($sections()[2]['props'])->toBe(['header' => 'Ward round 1', 'footer' => 'Revision 1 published by PHP']);

    $screen->tap('Rename section')->assertSet('sectionRevision', 2);
    expect($sections()[2]['props'])->toBe(['header' => 'Ward round 2', 'footer' => 'Revision 2 published by PHP']);

    $screen->tap('Remove header and footer')->assertSet('sectionNotesShown', false)
        ->assertSee('PHP accepted state: section revision 2 · header and footer removed');
    expect($sections()[2]['props'] ?? [])->toBe([])
        ->and($sections()[2]['children'])->toHaveCount(1);

    $screen->tap('Restore header and footer');
    expect($sections()[2]['props'])->toBe(['header' => 'Ward round 2', 'footer' => 'Revision 2 published by PHP']);

    $screen->tap('Bare section row')->assertSet('lastPressed', 'Bare section row');
});
