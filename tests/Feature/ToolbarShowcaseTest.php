<?php

use Native\Mobile\Testing\Native;

/** @return list<array<string, mixed>> */
function toolbarShowcaseItems(array $tree): array
{
    $nodes = ($tree['type'] ?? null) === 'firstlight.toolbar-item' ? [$tree] : [];

    foreach ($tree['children'] ?? [] as $child) {
        $nodes = [...$nodes, ...toolbarShowcaseItems($child)];
    }

    return $nodes;
}

/** @return array<string, array<string, mixed>> */
function toolbarShowcaseItemsByLabel(array $tree): array
{
    $items = toolbarShowcaseItems($tree);

    return array_combine(array_map(fn (array $item): string => $item['props']['a11y_label'], $items), $items);
}

it('publishes a Notes-style toolbar with a menu, a badge and a primary action', function () {
    $screen = Native::visit('/components/toolbar');
    $items = toolbarShowcaseItemsByLabel($screen->tree());

    expect($screen->tree()['props']['nav_title'] ?? null)->toBe('Firstlight Toolbar')
        ->and(array_keys($items))->toBe(['Checklist', 'Attach', 'Markup', 'Comments', 'New note'])
        ->and(array_column(array_column($items, 'props'), 'primary'))->toBe([false, false, false, false, true])
        ->and($items['Comments']['props']['badge'])->toBe('3')
        ->and($items['New note']['props']['haptic'])->toBe('keypress')
        ->and($items['Attach']['on_press'] ?? null)->toBeNull();

    $menu = json_decode($items['Attach']['props']['menu_json'], true, flags: JSON_THROW_ON_ERROR);
    expect(array_column($menu, 'label'))->toBe(['Photo', 'File', 'Scan document'])
        ->and(array_column($menu, 'disabled'))->toBe([false, false, true])
        ->and($menu[2]['cb'])->toBe(0);

    foreach (['Checklist', 'Markup', 'Comments', 'New note'] as $label) {
        expect($items[$label]['on_press'] ?? null)->toBeInt();
    }

    $screen->assertAccessible();
});

it('records toolbar actions and clears the comments badge', function () {
    $screen = Native::visit('/components/toolbar');

    $screen->tap('Checklist')->assertSet('lastAction', 'Checklist')->assertSee('Last action: Checklist');
    $screen->tap('New note')->assertSet('actionCount', 2)->assertSee('Last action: New note');
    $screen->tap('Comments')->assertSet('comments', 0)->assertSee('Unread comments: 0');

    expect(toolbarShowcaseItemsByLabel($screen->tree())['Comments']['props'])->not->toHaveKey('badge');

    $screen->tap('Add a comment')->assertSet('comments', 1);
    expect(toolbarShowcaseItemsByLabel($screen->tree())['Comments']['props']['badge'])->toBe('1');
});

it('disables Markup in place when it is switched off', function () {
    $screen = Native::visit('/components/toolbar')->set('markupAvailable', false);

    expect(toolbarShowcaseItemsByLabel($screen->tree())['Markup']['props']['disabled'])->toBeTrue();
});
