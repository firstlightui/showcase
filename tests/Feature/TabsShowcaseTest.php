<?php

use Native\Mobile\Testing\Native;

/** @return array<string, array<string, mixed>> */
function firstlightTabsNodesByLabel(array $tree): array
{
    $nodes = [];

    if (($tree['type'] ?? null) === 'firstlight.tabs') {
        $nodes[$tree['props']['a11y_label']] = $tree;
    }

    foreach ($tree['children'] ?? [] as $child) {
        $nodes = [...$nodes, ...firstlightTabsNodesByLabel($child)];
    }

    return $nodes;
}

it('publishes every documented Tabs state through the native wire tree', function () {
    $screen = Native::visit('/tabs');
    $tree = $screen->tree();
    $nodes = firstlightTabsNodesByLabel($tree);

    expect($tree['props']['title'] ?? null)->toBe('Firstlight Tabs')
        ->and(array_keys($nodes))->toBe([
            'Server sections',
            'Guarded sections',
            'No initial section',
            'No available sections',
            'Disabled sections',
            'Site sections',
        ])
        ->and($nodes['Server sections']['props']['tab_values'])->toBe(['overview', 'sites', 'deploys', 'manage'])
        ->and($nodes['Server sections']['props']['tab_badges'])->toBe(['', '', '2', ''])
        ->and($nodes['Server sections']['props']['tab_a11y_labels'][2])->toBe('Deployments, 2 failed')
        ->and($nodes['Server sections']['props']['tab_enabled'])->toBe(['1', '1', '1', '0'])
        ->and($nodes['Server sections']['props']['selected_value'])->toBe('overview')
        ->and($nodes['No initial section']['props']['has_selection'])->toBeFalse()
        ->and($nodes['No available sections']['props']['disabled'])->toBeTrue()
        ->and($nodes['Disabled sections']['props']['disabled'])->toBeTrue()
        ->and($nodes['Site sections']['props']['tab_badges'])->toBe(['', '99+', '', '', '']);

    $screen->assertSee('Overview pane')->assertDontSee('Sites pane')->assertAccessible();
});

it('renders only the selected pane and selects from PHP without a change event', function () {
    $screen = Native::visit('/tabs');

    $screen->select('tab', 'sites')
        ->assertSet('tab', 'sites')
        ->assertSee('Sites pane')
        ->assertDontSee('Overview pane');

    $screen->press('showDeployments')->assertSet('tab', 'deploys')->assertSee('Deployments pane');

    expect(firstlightTabsNodesByLabel($screen->tree())['Server sections']['props']['selected_value'])->toBe('deploys');
});

it('republishes badge counts as PHP state changes', function () {
    $screen = Native::visit('/tabs')->press('failAnotherDeployment');

    expect(firstlightTabsNodesByLabel($screen->tree())['Server sections']['props']['tab_a11y_labels'][2])
        ->toBe('Deployments, 3 failed');

    $screen->press('clearFailures');

    expect(firstlightTabsNodesByLabel($screen->tree())['Server sections']['props']['tab_badges'])
        ->toBe(['', '', '', '']);
});

it('keeps the accepted tab when the server rejects a change', function () {
    $screen = Native::visit('/tabs')
        ->select('selectGuardedTab', 'manage')
        ->assertSet('guardedTab', 'overview')
        ->assertSet('guardedAttempts', 1);

    expect(firstlightTabsNodesByLabel($screen->tree())['Guarded sections']['props']['selected_value'])->toBe('overview');

    $screen->select('selectGuardedTab', 'sites')->assertSet('guardedTab', 'sites')->assertSet('guardedAttempts', 2);
});
