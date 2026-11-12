<?php

use Native\Mobile\Testing\Native;

/** @return array<string, array<string, mixed>> */
function firstlightSelectsByLabel(array $tree): array
{
    $nodes = [];
    if (($tree['type'] ?? null) === 'firstlight.select') {
        $nodes[$tree['props']['label'] ?: $tree['props']['a11y_label']] = $tree;
    }
    foreach ($tree['children'] ?? [] as $child) {
        $nodes = [...$nodes, ...firstlightSelectsByLabel($child)];
    }

    return $nodes;
}

it('publishes the complete Select catalogue through the native wire tree', function () {
    $screen = Native::visit('/components/select');
    $tree = $screen->tree();
    $nodes = firstlightSelectsByLabel($tree);

    expect($tree['type'])->toBe('native_root_tabs')
        ->and($tree['props']['nav_title'] ?? null)->toBe('Firstlight Select')
        ->and(array_keys($nodes))->toBe([
            'Chip priority',
            'Priority',
            'Triage level',
            'Large queue',
            'Forced searchable queue',
            'Required priority',
            'Disabled priority',
            'Server-approved queue',
            'Ward',
            'Clinic',
            'Hospital site',
        ])
        ->and($nodes['Chip priority']['props'])->toMatchArray(['variant' => 'chip', 'selected_values' => []])
        ->and($nodes['Priority']['props'])->toMatchArray([
            'selected_values' => [],
            'value_type' => 'string',
            'placeholder' => 'Select a priority',
            'search_enabled' => false,
        ])
        ->and($nodes['Triage level']['props'])->toMatchArray([
            'selected_values' => ['20'],
            'value_type' => 'integer',
            'required' => true,
        ]);

    $screen->assertAccessible();
});

it('publishes automatic forced validation disabled and rejection fixtures', function () {
    $nodes = firstlightSelectsByLabel(Native::visit('/components/select')->tree());

    expect($nodes['Large queue']['props']['option_values'])->toHaveCount(13)
        ->and($nodes['Large queue']['props']['search_enabled'])->toBeTrue()
        ->and($nodes['Forced searchable queue']['props']['option_values'])->toHaveCount(2)
        ->and($nodes['Forced searchable queue']['props']['search_enabled'])->toBeTrue()
        ->and($nodes['Required priority']['props'])->toMatchArray([
            'selected_values' => [],
            'error' => 'Choose a priority.',
            'required' => true,
        ])->and($nodes['Disabled priority']['props']['disabled'])->toBeTrue()
        ->and($nodes['Server-approved queue']['props']['selected_values'])->toBe(['all']);
});

it('updates string and integer bindings while preserving a rejected publication', function () {
    $screen = Native::visit('/components/select');
    $screen
        ->press("__syncProperty('errorPriority', \"urgent\")")
        ->assertSet('errorPriority', 'urgent')
        ->assertSee('PHP accepted state: urgent')
        ->press('__syncProperty(\'priority\', "urgent")')
        ->assertSet('priority', 'urgent')
        ->press("__syncProperty('triageLevel', 30)")
        ->assertSet('triageLevel', 30)
        ->press('rejectQueue("mine")')
        ->assertSet('approvedQueue', 'all')
        ->assertSet('rejectedAttempts', 1)
        ->assertSee('Rejected attempts: 1');

    $nodes = firstlightSelectsByLabel($screen->tree());
    expect($nodes['Required priority']['props']['selected_values'])->toBe(['urgent'])
        ->and($nodes['Required priority']['props']['error'])->toBe('Choose a priority.')
        ->and($nodes['Triage level']['props']['selected_values'])->toBe(['30'])
        ->and($nodes['Chip priority']['props']['selected_values'])->toBe(['urgent']);
});

it('counts accepted choices and publishes programmatic set and clear without emitting', function () {
    $screen = Native::visit('/components/select');
    expect(firstlightSelectsByLabel($screen->tree())['Ward']['props'])->toMatchArray([
        'selected_values' => [],
        'placeholder' => 'Select a ward',
    ]);

    $screen->press('acceptWard("ward-1")')
        ->assertSet('ward', 'ward-1')
        ->assertSee('PHP accepted state: ward-1; Accepted changes: 1')
        ->tap('Set ward from PHP')
        ->assertSet('ward', 'ward-3')
        ->assertSet('acceptedWardChanges', 1);

    $ward = firstlightSelectsByLabel($screen->tree())['Ward']['props'];
    expect($ward['selected_values'])->toBe(['ward-3'])
        // The accepted value has no proposal callback; the others still emit.
        ->and($ward['option_callbacks'][2])->toBe('0')
        ->and($ward['option_callbacks'][0])->not->toBe('0');

    $screen->tap('Clear ward from PHP')
        ->assertSet('ward', null)
        ->assertSee('PHP accepted state: (empty); Accepted changes: 1');
    expect(firstlightSelectsByLabel($screen->tree())['Ward']['props']['selected_values'])->toBe([]);
});

it('publishes an inert empty select and a select named only by a11y-label', function () {
    $screen = Native::visit('/components/select');
    $nodes = firstlightSelectsByLabel($screen->tree());

    expect($nodes['Clinic']['props'])->toMatchArray([
        'disabled' => true,
        'option_values' => [],
        'option_callbacks' => [],
        'selected_values' => [],
        'placeholder' => 'No clinics available',
    ])->and($nodes['Hospital site']['props'])->toMatchArray([
        'label' => '',
        'a11y_label' => 'Hospital site',
        'a11y_hint' => 'Choose where the appointment takes place',
        'selected_values' => ['north'],
    ]);

    $screen->press("__syncProperty('site', \"south\")")
        ->assertSet('site', 'south')
        ->assertSee('PHP accepted state: south');
});
