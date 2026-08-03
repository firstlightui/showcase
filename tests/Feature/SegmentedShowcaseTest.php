<?php

use Native\Mobile\Testing\Native;

/** @return array<string, array<string, mixed>> */
function firstlightSegmentedNodesByLabel(array $tree): array
{
    $nodes = [];

    if (($tree['type'] ?? null) === 'firstlight.segmented') {
        $nodes[$tree['props']['label']] = $tree;
    }

    foreach ($tree['children'] ?? [] as $child) {
        $nodes = [...$nodes, ...firstlightSegmentedNodesByLabel($child)];
    }

    return $nodes;
}

/** @return array<string, mixed> */
function firstlightPropsWithoutCallbacks(array $node): array
{
    return array_diff_key($node['props'], array_flip(['on_change', 'option_callbacks']));
}

it('defines accessible primary color pairs for both appearances', function () {
    expect(config('native-ui.theme.light.primary'))->toBe('#0F766E')
        ->and(config('native-ui.theme.light.on-primary'))->toBe('#FFFFFF')
        ->and(config('native-ui.theme.dark.primary'))->toBe('#14B8A6')
        ->and(config('native-ui.theme.dark.on-primary'))->toBe('#000000');
});

it('publishes the complete Segmented catalogue through the native wire tree', function () {
    $screen = Native::visit('/segmented');

    $tree = $screen->tree();
    $nodes = firstlightSegmentedNodesByLabel($tree);

    expect($tree['type'])->toBe('native_root_stack')
        ->and($tree['props']['title'] ?? null)->toBe('Firstlight Segmented')
        ->and(array_keys($nodes))->toBe([
            'Priority',
            'Queue',
            'Simple queue',
            'Stable queue',
            'No initial selection',
            'No available options',
            'Disabled group',
            'Queue with error',
            'Required queue',
            'Workflow status',
        ]);

    $screen->assertAccessible();
});

it('publishes simple and stable string values with distinct model callbacks', function () {
    $nodes = firstlightSegmentedNodesByLabel(Native::visit('/segmented')->tree());

    $simple = $nodes['Simple queue'];
    $stable = $nodes['Stable queue'];

    expect(firstlightPropsWithoutCallbacks($simple))->toBe([
        'label' => 'Simple queue',
        'helper' => '',
        'error' => '',
        'required' => false,
        'disabled' => false,
        'value_type' => 'string',
        'has_selection' => true,
        'selected_value' => 'Mine',
        'option_values' => ['Mine', 'All'],
        'option_labels' => ['Mine', 'All'],
        'option_enabled' => ['1', '1'],
    ])->and($simple['props']['on_change'])->toBeInt()
        ->and(firstlightPropsWithoutCallbacks($stable))->toBe([
            'label' => 'Stable queue',
            'helper' => '',
            'error' => '',
            'required' => false,
            'disabled' => false,
            'value_type' => 'string',
            'has_selection' => true,
            'selected_value' => 'mine',
            'option_values' => ['mine', 'all'],
            'option_labels' => ['Mine', 'All'],
            'option_enabled' => ['1', '1'],
        ])->and($stable['props']['on_change'])->toBeInt()
        ->and($stable['props']['on_change'])->not->toBe($simple['props']['on_change']);
});

it('serializes integer values with per-option availability and callback IDs', function () {
    $priority = firstlightSegmentedNodesByLabel(Native::visit('/segmented')->tree())['Priority'];
    $callbacks = $priority['props']['option_callbacks'];

    expect(firstlightPropsWithoutCallbacks($priority))->toBe([
        'a11y_hint' => 'Choose a referral priority',
        'label' => 'Priority',
        'helper' => 'Routine is selected; Urgent is unavailable.',
        'error' => '',
        'required' => false,
        'disabled' => false,
        'value_type' => 'integer',
        'has_selection' => true,
        'selected_value' => '10',
        'option_values' => ['10', '20'],
        'option_labels' => ['Routine', 'Urgent'],
        'option_enabled' => ['1', '0'],
    ])->and($priority['props'])->not->toHaveKey('on_change')
        ->and($callbacks)->toHaveCount(2)
        ->and($callbacks[0])->toBeString()->toMatch('/^\d+$/')
        ->and($callbacks[1])->toBeString()->toMatch('/^\d+$/')
        ->and($callbacks[1])->not->toBe($callbacks[0]);
});

it('publishes null and empty options without an implicit selection', function () {
    $nodes = firstlightSegmentedNodesByLabel(Native::visit('/segmented')->tree());

    $unselected = $nodes['No initial selection'];
    $empty = $nodes['No available options'];

    expect(firstlightPropsWithoutCallbacks($unselected))->toBe([
        'label' => 'No initial selection',
        'helper' => 'Null leaves every segment unselected.',
        'error' => '',
        'required' => false,
        'disabled' => false,
        'value_type' => 'string',
        'has_selection' => false,
        'selected_value' => '',
        'option_values' => ['mine', 'all'],
        'option_labels' => ['Mine', 'All'],
        'option_enabled' => ['1', '1'],
    ])->and($unselected['props']['on_change'])->toBeInt()
        ->and($empty['props'])->toBe([
            'label' => 'No available options',
            'helper' => 'An empty option list is inert.',
            'error' => '',
            'required' => false,
            'disabled' => true,
            'value_type' => 'string',
            'has_selection' => false,
            'selected_value' => '',
            'option_values' => [],
            'option_labels' => [],
            'option_enabled' => [],
        ])->and($empty['props'])->not->toHaveKeys(['on_change', 'option_callbacks']);
});

it('publishes disabled, validation, helper, and accessibility metadata', function () {
    $nodes = firstlightSegmentedNodesByLabel(Native::visit('/segmented')->tree());

    expect(firstlightPropsWithoutCallbacks($nodes['Disabled group']))->toBe([
        'label' => 'Disabled group',
        'helper' => '',
        'error' => '',
        'required' => false,
        'disabled' => true,
        'value_type' => 'string',
        'has_selection' => true,
        'selected_value' => 'locked',
        'option_values' => ['locked', 'archived'],
        'option_labels' => ['Locked', 'Archived'],
        'option_enabled' => ['1', '1'],
    ])->and(firstlightPropsWithoutCallbacks($nodes['Queue with error']))->toBe([
        'label' => 'Queue with error',
        'helper' => '',
        'error' => 'Choose a queue before continuing.',
        'required' => false,
        'disabled' => false,
        'value_type' => 'string',
        'has_selection' => true,
        'selected_value' => 'mine',
        'option_values' => ['mine', 'all'],
        'option_labels' => ['Mine', 'All'],
        'option_enabled' => ['1', '1'],
    ])->and(firstlightPropsWithoutCallbacks($nodes['Required queue']))->toBe([
        'label' => 'Required queue',
        'helper' => 'This selection is required.',
        'error' => '',
        'required' => true,
        'disabled' => false,
        'value_type' => 'string',
        'has_selection' => true,
        'selected_value' => 'mine',
        'option_values' => ['mine', 'all'],
        'option_labels' => ['Mine', 'All'],
        'option_enabled' => ['1', '1'],
    ])->and(firstlightPropsWithoutCallbacks($nodes['Workflow status']))->toBe([
        'a11y_hint' => 'Choose the workflow status',
        'label' => 'Workflow status',
        'helper' => '',
        'error' => '',
        'required' => false,
        'disabled' => false,
        'value_type' => 'string',
        'has_selection' => true,
        'selected_value' => 'clinical-review',
        'option_values' => ['clinical-review', 'assignment'],
        'option_labels' => ['Awaiting clinical review', 'Ready for assignment'],
        'option_enabled' => ['1', '1'],
    ]);
});

it('dispatches the bound queue selection and server reset through published callbacks', function () {
    $screen = Native::visit('/segmented');
    $nodes = firstlightSegmentedNodesByLabel($screen->tree());

    expect(firstlightPropsWithoutCallbacks($nodes['Queue']))->toBe([
        'a11y_hint' => 'Choose the active queue',
        'label' => 'Queue',
        'helper' => 'Change the queue, then reset it from the server.',
        'error' => '',
        'required' => false,
        'disabled' => false,
        'value_type' => 'string',
        'has_selection' => true,
        'selected_value' => 'mine',
        'option_values' => ['mine', 'all'],
        'option_labels' => ['Mine', 'All'],
        'option_enabled' => ['1', '1'],
    ])->and($nodes['Queue']['props']['on_change'])->toBeInt()
        ->and($nodes['Stable queue']['props']['on_change'])->toBe($nodes['Queue']['props']['on_change']);

    $screen->select('queue', 'all')->assertSet('queue', 'all');

    $selectedNodes = firstlightSegmentedNodesByLabel($screen->tree());

    expect($selectedNodes['Queue']['props']['selected_value'])->toBe('all')
        ->and($selectedNodes['Stable queue']['props']['selected_value'])->toBe('all');

    $screen->assertElement('button', fn (array $node): bool =>
        ($node['props']['label'] ?? null) === 'Reset selections'
        && ($node['props']['variant'] ?? null) === 'primary'
        && is_int($node['props']['on_press'] ?? null)
    )->press('resetSelections')
        ->assertSet('queue', 'mine')
        ->assertSet('priority', 10);

    $resetNodes = firstlightSegmentedNodesByLabel($screen->tree());

    expect($resetNodes['Queue']['props']['selected_value'])->toBe('mine')
        ->and($resetNodes['Stable queue']['props']['selected_value'])->toBe('mine');
});
