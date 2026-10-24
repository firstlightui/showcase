<?php

use Native\Mobile\Testing\Native;

it('publishes the stable List Item context menu capture fixture', function () {
    $screen = Native::visit('/captures/list-item-context-menu');
    $find = function (array $tree) use (&$find): ?array {
        if (($tree['type'] ?? null) === 'firstlight.list-item') {
            return $tree;
        }
        foreach ($tree['children'] ?? [] as $child) {
            if ($found = $find($child)) {
                return $found;
            }
        }

        return null;
    };
    $node = $find($screen->tree());
    $menu = json_decode($node['props']['firstlight_context_menu_json'], true, flags: JSON_THROW_ON_ERROR);

    expect($screen->tree()['props']['title'] ?? null)->toBe('Firstlight Context Menu')
        ->and($node['props']['headline'])->toBe('Message from Alex')
        ->and(array_column($menu, 'label'))->toBe(['Reply', 'Forward', 'Pin', 'Delete'])
        ->and(array_column($menu, 'disabled'))->toBe([false, true, false, false])
        ->and(array_column($menu, 'role'))->toBe(['', '', '', 'destructive'])
        ->and($menu[1]['cb'])->toBe(0);

    $screen->assertAccessible();
});
