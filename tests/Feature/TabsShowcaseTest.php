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
    $screen = Native::visit('/components/tabs');
    $tree = $screen->tree();
    $nodes = firstlightTabsNodesByLabel($tree);

    expect($tree['props']['nav_title'] ?? null)->toBe('Firstlight Tabs')
        ->and(array_keys($nodes))->toBe([
            'Server sections',
            'Guarded sections',
            'Live server sections',
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
    $screen = Native::visit('/components/tabs');

    $screen->select('tab', 'sites')
        ->assertSet('tab', 'sites')
        ->assertSee('Sites pane')
        ->assertDontSee('Overview pane');

    $screen->press('showDeployments')->assertSet('tab', 'deploys')->assertSee('Deployments pane');

    expect(firstlightTabsNodesByLabel($screen->tree())['Server sections']['props']['selected_value'])->toBe('deploys');
});

it('republishes badge counts as PHP state changes', function () {
    $screen = Native::visit('/components/tabs')->press('failAnotherDeployment');

    expect(firstlightTabsNodesByLabel($screen->tree())['Server sections']['props']['tab_a11y_labels'][2])
        ->toBe('Deployments, 3 failed');

    $screen->press('clearFailures');

    expect(firstlightTabsNodesByLabel($screen->tree())['Server sections']['props']['tab_badges'])
        ->toBe(['', '', '', '']);
});

it('keeps the accepted tab when the server rejects a change', function () {
    $screen = Native::visit('/components/tabs')
        ->select('selectGuardedTab', 'manage')
        ->assertSet('guardedTab', 'overview')
        ->assertSet('guardedAttempts', 1);

    expect(firstlightTabsNodesByLabel($screen->tree())['Guarded sections']['props']['selected_value'])->toBe('overview');

    $screen->select('selectGuardedTab', 'sites')->assertSet('guardedTab', 'sites')->assertSet('guardedAttempts', 2);
});

it('lets a11y-label override label and syncs native:model.live', function () {
    $screen = Native::visit('/components/tabs');
    $live = firstlightTabsNodesByLabel($screen->tree())['Live server sections'];

    expect($live['props'])->toMatchArray([
        'a11y_label' => 'Live server sections',
        'selected_value' => 'overview',
        'tab_values' => ['overview', 'sites', 'deploys', 'manage'],
    ])->and($live['props']['on_change'])->toBeInt()
        ->and(array_keys(firstlightTabsNodesByLabel($screen->tree())))->not->toContain('Live sections');

    $screen->select('liveTab', 'sites')->assertSet('liveTab', 'sites')->assertSee('live sites');

    expect(firstlightTabsNodesByLabel($screen->tree())['Live server sections']['props']['selected_value'])->toBe('sites');
});

it('counts accepted tab selections but not programmatic selection', function () {
    Native::visit('/components/tabs')
        ->assertSee('tab changes 0')
        ->select('tab', 'sites')
        ->select('tab', 'overview')
        ->assertSet('tabChanges', 2)
        ->press('showDeployments')
        ->assertSet('tab', 'deploys')
        ->assertSet('tabChanges', 2)
        ->assertSee('tab changes 2');
});

it('opens a section from the optional route segment and ignores unknown values', function () {
    $nodes = fn ($screen) => firstlightTabsNodesByLabel($screen->tree());

    $deploys = Native::visit('/components/tabs/deploys');
    expect($nodes($deploys)['Server sections']['props']['selected_value'])->toBe('deploys');
    $deploys->assertSee('Deployments pane')->assertDontSee('Overview pane');

    $unknown = Native::visit('/components/tabs/billing');
    expect($nodes($unknown)['Server sections']['props']['selected_value'])->toBe('overview');

    Native::visit('/components/tabs')
        ->press('openDeploymentsByLink')
        ->assertNavigatedTo('/components/tabs/deploys');
});
