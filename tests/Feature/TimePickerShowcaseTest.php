<?php

use Native\Mobile\Testing\Native;

/** @return array<string, array<string, mixed>> */
function firstlightTimePickersByLabel(array $tree): array
{
    $nodes = [];
    if (($tree['type'] ?? null) === 'firstlight.time-picker') {
        $nodes[$tree['props']['label']] = $tree;
    }
    foreach ($tree['children'] ?? [] as $child) {
        $nodes = [...$nodes, ...firstlightTimePickersByLabel($child)];
    }

    return $nodes;
}

it('publishes the complete Time Picker catalogue through the native wire tree', function () {
    $screen = Native::visit('/time-picker');
    $tree = $screen->tree();
    $nodes = firstlightTimePickersByLabel($tree);

    expect($tree['type'])->toBe('native_root_stack')
        ->and($tree['props']['title'] ?? null)->toBe('Firstlight Time Picker')
        ->and(array_keys($nodes))->toBe([
            'Appointment time',
            'Review time',
            'Arrival time',
            'Berlin clinic time',
            'Disabled time',
            'Server-approved time',
            'Programmatic time',
        ])
        ->and($nodes['Appointment time']['props'])->toMatchArray([
            'has_value' => false,
            'value' => '',
            'placeholder' => 'Choose a time',
            'locale' => 'en-AU',
            'timezone' => 'Australia/Sydney',
        ])
        ->and($nodes['Review time']['props'])->toMatchArray([
            'has_value' => true,
            'value' => '14:30',
            'required' => true,
        ]);

    $screen->assertAccessible();
});

it('publishes omitted error localized disabled and reconciliation fixtures', function () {
    $nodes = firstlightTimePickersByLabel(Native::visit('/time-picker')->tree());

    expect($nodes['Arrival time']['props'])->toMatchArray([
        'has_value' => false,
        'value' => '',
        'error' => 'Choose an arrival time.',
    ])->and($nodes['Berlin clinic time']['props'])->toMatchArray([
        'value' => '18:30',
        'locale' => 'de-DE',
        'timezone' => 'Europe/Berlin',
    ])->and($nodes['Disabled time']['props']['disabled'])->toBeTrue()
        ->and($nodes['Server-approved time']['props']['value'])->toBe('09:15')
        ->and($nodes['Programmatic time']['props']['a11y_hint'])->toBe('Use the buttons below to replace or clear the published value');
});

it('updates bound times rejects one proposal and applies programmatic publications', function () {
    Native::visit('/time-picker')
        ->select('appointmentTime', '10:45')
        ->assertSet('appointmentTime', '10:45')
        ->select('rejectedTime', '09:30')
        ->assertSet('approvedTime', '09:15')
        ->assertSet('rejectedAttempts', 1)
        ->press('publishLaterTime')
        ->assertSet('programmaticTime', '17:45')
        ->press('clearProgrammaticTime')
        ->assertSet('programmaticTime', null);
});
