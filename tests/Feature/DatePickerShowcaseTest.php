<?php

use Native\Mobile\Testing\Native;

/** @return array<string, array<string, mixed>> */
function firstlightDatePickersByLabel(array $tree): array
{
    $nodes = [];
    if (($tree['type'] ?? null) === 'firstlight.date-picker') {
        $nodes[$tree['props']['label']] = $tree;
    }
    foreach ($tree['children'] ?? [] as $child) {
        $nodes = [...$nodes, ...firstlightDatePickersByLabel($child)];
    }

    return $nodes;
}

it('publishes the complete Date Picker catalogue through the native wire tree', function () {
    $screen = Native::visit('/date-picker');
    $tree = $screen->tree();
    $nodes = firstlightDatePickersByLabel($tree);

    expect($tree['type'])->toBe('native_root_stack')
        ->and($tree['props']['title'] ?? null)->toBe('Firstlight Date Picker')
        ->and(array_keys($nodes))->toBe([
            'Appointment date',
            'Review date',
            'Discharge date',
            'Berlin clinic date',
            'Disabled date',
            'Server-approved date',
            'Programmatic date',
        ])
        ->and($nodes['Appointment date']['props'])->toMatchArray([
            'has_value' => false,
            'value' => '',
            'placeholder' => 'Choose a date',
            'locale' => 'en-AU',
            'timezone' => 'Australia/Sydney',
        ])
        ->and($nodes['Review date']['props'])->toMatchArray([
            'has_value' => true,
            'value' => '2026-08-04',
            'min' => '2026-08-01',
            'max' => '2026-08-31',
            'required' => true,
        ]);

    $screen->assertAccessible();
});

it('publishes omitted error localized disabled and reconciliation fixtures', function () {
    $nodes = firstlightDatePickersByLabel(Native::visit('/date-picker')->tree());

    expect($nodes['Discharge date']['props'])->toMatchArray([
        'has_value' => false,
        'value' => '',
        'error' => 'Choose a discharge date.',
    ])->and($nodes['Berlin clinic date']['props'])->toMatchArray([
        'value' => '2026-12-24',
        'locale' => 'de-DE',
        'timezone' => 'Europe/Berlin',
    ])->and($nodes['Disabled date']['props']['disabled'])->toBeTrue()
        ->and($nodes['Server-approved date']['props']['value'])->toBe('2026-08-12')
        ->and($nodes['Programmatic date']['props']['a11y_hint'])->toBe('Use the buttons below to replace or clear the published value');
});

it('updates bound dates rejects one proposal and applies programmatic publications', function () {
    Native::visit('/date-picker')
        ->select('appointmentDate', '2026-08-18')
        ->assertSet('appointmentDate', '2026-08-18')
        ->select('rejectedDate', '2026-08-13')
        ->assertSet('approvedDate', '2026-08-12')
        ->assertSet('rejectedAttempts', 1)
        ->press('publishLaterDate')
        ->assertSet('programmaticDate', '2026-11-15')
        ->press('clearProgrammaticDate')
        ->assertSet('programmaticDate', null);
});
