<?php

use Native\Mobile\Testing\Native;

/** @return array<string, array<string, mixed>> */
function firstlightDatePickersByLabel(array $tree): array
{
    $nodes = [];
    if (($tree['type'] ?? null) === 'firstlight.date-picker') {
        $nodes[$tree['props']['label'] ?: $tree['props']['a11y_label']] = $tree;
    }
    foreach ($tree['children'] ?? [] as $child) {
        $nodes = [...$nodes, ...firstlightDatePickersByLabel($child)];
    }

    return $nodes;
}

it('publishes the complete Date Picker catalogue through the native wire tree', function () {
    $screen = Native::visit('/components/date-picker');
    $tree = $screen->tree();
    $nodes = firstlightDatePickersByLabel($tree);

    expect($tree['type'])->toBe('native_root_tabs')
        ->and($tree['props']['nav_title'] ?? null)->toBe('Firstlight Date Picker')
        ->and(array_keys($nodes))->toBe([
            'DOS',
            'Appointment date',
            'Review date',
            'Discharge date',
            'Berlin clinic date',
            'Disabled date',
            'Inherited locale date',
            'Follow-up date',
            'Open picker',
            'Server-approved date',
            'Programmatic date',
        ])
        ->and($nodes['DOS']['props'])->toMatchArray([
            'variant' => 'chip', 'display_text' => '4 Aug 2026', 'value' => '2026-08-04',
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
    $nodes = firstlightDatePickersByLabel(Native::visit('/components/date-picker')->tree());

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
    $screen = Native::visit('/components/date-picker');
    $screen
        ->select('errorDate', '2026-08-19')
        ->assertSet('errorDate', '2026-08-19')
        ->assertSee('PHP accepted state: 2026-08-19')
        ->select('berlinDate', '2026-12-25')
        ->assertSee('PHP accepted state: 2026-12-25')
        ->select('appointmentDate', '2026-08-18')
        ->assertSet('appointmentDate', '2026-08-18')
        ->select('rejectedDate', '2026-08-13')
        ->assertSet('approvedDate', '2026-08-12')
        ->assertSet('rejectedAttempts', 1)
        ->assertSee('Rejected attempts: 1')
        ->tap('Publish later date')
        ->assertSet('programmaticDate', '2026-11-15')
        ->tap('Clear date')
        ->assertSet('programmaticDate', null);

    $nodes = firstlightDatePickersByLabel($screen->tree());
    expect($nodes['Discharge date']['props']['value'])->toBe('2026-08-19')
        ->and($nodes['Discharge date']['props']['error'])->toBe('Choose a discharge date.')
        ->and($nodes['Berlin clinic date']['props']['value'])->toBe('2026-12-25')
        ->and($nodes['Programmatic date']['props']['has_value'])->toBeFalse();
});

it('inherits the application locale and timezone and names a label-less field by a11y-label', function () {
    $screen = Native::visit('/components/date-picker');
    $nodes = firstlightDatePickersByLabel($screen->tree());

    expect($nodes['Inherited locale date']['props'])->toMatchArray([
        'value' => '2026-09-03',
        'locale' => app()->getLocale(),
        'timezone' => config('app.timezone'),
    ])->and($nodes['Follow-up date']['props'])->toMatchArray([
        'label' => '',
        'a11y_label' => 'Follow-up date',
        'a11y_hint' => 'Choose when to review the patient again',
        'has_value' => false,
    ]);

    $screen->select('followUpDate', '2026-09-17')
        ->assertSee('follow-up: 2026-09-17');
});

it('publishes scheduled value bounds and disabled changes for an open picker', function () {
    $screen = Native::visit('/components/date-picker');
    expect(firstlightDatePickersByLabel($screen->tree())['Open picker']['props'])->toMatchArray([
        'value' => '2026-09-10', 'min' => '2026-09-01', 'max' => '2026-09-30', 'disabled' => false,
    ]);

    $screen->tap('Change value')
        ->assertSee('pending: value')
        ->firePoll('applyOpenChange')
        ->assertSet('openDate', '2026-09-10')
        ->set('pendingOpenChangeAt', time() - 1)
        ->firePoll('applyOpenChange')
        ->assertSee('last change: value');
    expect(firstlightDatePickersByLabel($screen->tree())['Open picker']['props']['value'])->toBe('2026-09-20');

    $screen->tap('Change bounds')->set('pendingOpenChangeAt', time() - 1)->firePoll('applyOpenChange');
    expect(firstlightDatePickersByLabel($screen->tree())['Open picker']['props'])->toMatchArray([
        'min' => '2026-08-15', 'max' => '2026-10-15',
    ]);

    $screen->tap('Disable')->set('pendingOpenChangeAt', time() - 1)->firePoll('applyOpenChange');
    expect(firstlightDatePickersByLabel($screen->tree())['Open picker']['props']['disabled'])->toBeTrue();

    $screen->tap('Reset open picker')->assertSee('last change: (none)');
    expect(firstlightDatePickersByLabel($screen->tree())['Open picker']['props'])->toMatchArray([
        'value' => '2026-09-10', 'min' => '2026-09-01', 'max' => '2026-09-30', 'disabled' => false,
    ]);
});
