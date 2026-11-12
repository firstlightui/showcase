<?php

use Native\Mobile\Testing\Native;

/** @return array<string, array<string, mixed>> */
function firstlightTimePickersByLabel(array $tree): array
{
    $nodes = [];
    if (($tree['type'] ?? null) === 'firstlight.time-picker') {
        $nodes[$tree['props']['label'] ?: $tree['props']['a11y_label']] = $tree;
    }
    foreach ($tree['children'] ?? [] as $child) {
        $nodes = [...$nodes, ...firstlightTimePickersByLabel($child)];
    }

    return $nodes;
}

it('publishes the complete Time Picker catalogue through the native wire tree', function () {
    $screen = Native::visit('/components/time-picker');
    $tree = $screen->tree();
    $nodes = firstlightTimePickersByLabel($tree);

    expect($tree['type'])->toBe('native_root_tabs')
        ->and($tree['props']['nav_title'] ?? null)->toBe('Firstlight Time Picker')
        ->and(array_keys($nodes))->toBe([
            'Appointment time',
            'Review time',
            'Arrival time',
            'Berlin clinic time',
            'New York clinic time',
            'Disabled time',
            'Medication time',
            'Open time picker',
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
    $nodes = firstlightTimePickersByLabel(Native::visit('/components/time-picker')->tree());

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
    $screen = Native::visit('/components/time-picker');
    $screen
        ->select('errorTime', '11:20')
        ->assertSet('errorTime', '11:20')
        ->assertSee('PHP accepted state: 11:20')
        ->select('berlinTime', '19:45')
        ->assertSee('PHP accepted state: 19:45')
        ->select('appointmentTime', '10:45')
        ->assertSet('appointmentTime', '10:45')
        ->select('rejectedTime', '09:30')
        ->assertSet('approvedTime', '09:15')
        ->assertSet('rejectedAttempts', 1)
        ->assertSee('Rejected attempts: 1')
        ->tap('Publish later time')
        ->assertSet('programmaticTime', '17:45')
        ->tap('Clear time')
        ->assertSet('programmaticTime', null);

    $nodes = firstlightTimePickersByLabel($screen->tree());
    expect($nodes['Arrival time']['props']['value'])->toBe('11:20')
        ->and($nodes['Arrival time']['props']['error'])->toBe('Choose an arrival time.')
        ->and($nodes['Berlin clinic time']['props']['value'])->toBe('19:45')
        ->and($nodes['Programmatic time']['props']['has_value'])->toBeFalse();
});

it('publishes a 12-hour locale beside 24-hour German and a field named only by a11y-label', function () {
    $screen = Native::visit('/components/time-picker');
    $nodes = firstlightTimePickersByLabel($screen->tree());

    expect($nodes['New York clinic time']['props'])->toMatchArray([
        'value' => '18:30',
        'locale' => 'en-US',
        'timezone' => 'America/New_York',
    ])->and($nodes['Berlin clinic time']['props']['locale'])->toBe('de-DE')
        ->and($nodes['Medication time']['props'])->toMatchArray([
            'label' => '',
            'a11y_label' => 'Medication time',
            'a11y_hint' => 'Choose when the next dose is due',
            'has_value' => false,
        ]);

    $screen->select('newYorkTime', '07:05')
        ->assertSee('PHP accepted state: 07:05 (12-hour display)')
        ->select('medicationTime', '22:00')
        ->assertSet('medicationTime', '22:00');
});

it('publishes scheduled value and disabled changes for an open picker', function () {
    $screen = Native::visit('/components/time-picker');
    expect(firstlightTimePickersByLabel($screen->tree())['Open time picker']['props'])->toMatchArray([
        'value' => '08:00', 'disabled' => false,
    ]);

    $screen->tap('Change time')
        ->assertSee('pending: value')
        ->firePoll('applyOpenChange')
        ->assertSet('openTime', '08:00')
        ->set('pendingOpenChangeAt', time() - 1)
        ->firePoll('applyOpenChange')
        ->assertSee('last change: value');
    expect(firstlightTimePickersByLabel($screen->tree())['Open time picker']['props']['value'])->toBe('12:45');

    $screen->tap('Disable')->set('pendingOpenChangeAt', time() - 1)->firePoll('applyOpenChange');
    expect(firstlightTimePickersByLabel($screen->tree())['Open time picker']['props']['disabled'])->toBeTrue();

    $screen->tap('Reset open picker')->assertSee('last change: (none)');
    expect(firstlightTimePickersByLabel($screen->tree())['Open time picker']['props'])->toMatchArray([
        'value' => '08:00', 'disabled' => false,
    ]);
});
