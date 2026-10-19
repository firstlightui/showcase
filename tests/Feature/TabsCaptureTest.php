<?php

use Native\Mobile\Testing\Native;

it('publishes the stable Tabs documentation capture fixture', function () {
    $tree = Native::visit('/captures/tabs')->tree();
    $nodes = firstlightTabsNodesByLabel($tree);

    expect($tree['props']['title'] ?? null)->toBe('Firstlight Tabs')
        ->and(array_keys($nodes))->toBe(['Server sections', 'Site sections'])
        ->and($nodes['Server sections']['props']['selected_value'])->toBe('deploys')
        ->and($nodes['Server sections']['props']['tab_badges'])->toBe(['', '', '2', ''])
        ->and($nodes['Server sections']['props']['tab_enabled'])->toBe(['1', '1', '1', '0'])
        ->and($nodes['Site sections']['props']['tab_labels'])->toBe(['Overview', 'Deploys', 'Env']);

    Native::visit('/captures/tabs')->assertAccessible();
});
