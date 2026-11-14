<?php

use Native\Mobile\Testing\Native;

/** @return list<array<string, mixed>> */
function toolbarCaptureItems(array $tree): array
{
    $nodes = ($tree['type'] ?? null) === 'firstlight.toolbar-item' ? [$tree] : [];

    foreach ($tree['children'] ?? [] as $child) {
        $nodes = [...$nodes, ...toolbarCaptureItems($child)];
    }

    return $nodes;
}

it('publishes the stable Toolbar documentation capture fixture', function () {
    $screen = Native::visit('/captures/toolbar');
    $props = array_column(toolbarCaptureItems($screen->tree()), 'props');

    expect($screen->tree()['props']['title'] ?? null)->toBe('Firstlight Toolbar')
        ->and(array_column($props, 'a11y_label'))->toBe(['Checklist', 'Attach', 'Markup', 'New note'])
        ->and(array_column($props, 'primary'))->toBe([false, false, false, true])
        ->and(array_column($props, 'disabled'))->toBe([false, false, false, false]);

    $screen->assertAccessible();
});
