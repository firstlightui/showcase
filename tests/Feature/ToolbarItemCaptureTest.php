<?php

use Native\Mobile\Testing\Native;

/** @return list<array<string, mixed>> */
function toolbarItemCaptureItems(array $tree): array
{
    $nodes = ($tree['type'] ?? null) === 'firstlight.toolbar-item' ? [$tree] : [];

    foreach ($tree['children'] ?? [] as $child) {
        $nodes = [...$nodes, ...toolbarItemCaptureItems($child)];
    }

    return $nodes;
}

it('publishes the stable Toolbar Item documentation capture fixture', function () {
    $screen = Native::visit('/captures/toolbar-item');
    $items = toolbarItemCaptureItems($screen->tree());
    $props = array_column($items, 'props');

    expect($screen->tree()['props']['title'] ?? null)->toBe('Firstlight Toolbar Item')
        ->and(array_column($props, 'a11y_label'))->toBe(['Inbox', 'Archive', 'More', 'New message'])
        ->and($props[0]['badge'])->toBe('3')
        ->and($props[1]['disabled'])->toBeTrue()
        ->and($props[3]['primary'])->toBeTrue()
        ->and(array_column(json_decode($props[2]['menu_json'], true, flags: JSON_THROW_ON_ERROR), 'role'))->toBe(['', 'destructive']);

    $screen->assertAccessible();
});
