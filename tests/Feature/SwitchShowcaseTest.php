<?php

use Native\Mobile\Testing\Native;

/** @return array<string, array<string, mixed>> */
function firstlightSwitchNodesByLabel(array $tree): array
{
    $nodes = [];

    if (($tree['type'] ?? null) === 'firstlight.switch') {
        $nodes[$tree['props']['label'] ?: $tree['props']['a11y_label']] = $tree;
    }

    foreach ($tree['children'] ?? [] as $child) {
        $nodes = [...$nodes, ...firstlightSwitchNodesByLabel($child)];
    }

    return $nodes;
}

/** @return array<string, bool|string> */
function firstlightSwitchPrimitiveProps(array $node): array
{
    return array_diff_key($node['props'], array_flip(['on_change', 'ref']));
}

it('publishes the complete Switch catalogue through the native wire tree', function () {
    $screen = Native::visit('/components/switch');
    $nodes = firstlightSwitchNodesByLabel($screen->tree());

    expect($screen->tree()['props']['nav_title'] ?? null)->toBe('Firstlight Switch')
        ->and(array_keys($nodes))->toBe([
            'Notifications off',
            'Notifications on',
            'Disabled off',
            'Disabled on',
            'Helper text',
            'Validation error',
            'Rejected setting',
            'Programmatic setting',
            'Appointment reminders',
            'Share records',
            'A considerably longer setting label that wraps naturally',
        ])
        ->and(firstlightSwitchPrimitiveProps($nodes['Notifications off']))->toBe([
            'value' => false,
            'label' => 'Notifications off',
            'helper' => '',
            'error' => '',
            'disabled' => false,
        ])
        ->and(firstlightSwitchPrimitiveProps($nodes['Notifications on']))->toBe([
            'value' => true,
            'label' => 'Notifications on',
            'helper' => '',
            'error' => '',
            'disabled' => false,
        ])
        ->and(firstlightSwitchPrimitiveProps($nodes['Disabled off']))->toBe([
            'value' => false,
            'label' => 'Disabled off',
            'helper' => '',
            'error' => '',
            'disabled' => true,
        ])
        ->and(firstlightSwitchPrimitiveProps($nodes['Disabled on']))->toBe([
            'value' => true,
            'label' => 'Disabled on',
            'helper' => '',
            'error' => '',
            'disabled' => true,
        ])
        ->and(firstlightSwitchPrimitiveProps($nodes['Helper text']))->toBe([
            'value' => false,
            'label' => 'Helper text',
            'helper' => 'Receive updates when this setting is enabled.',
            'error' => '',
            'disabled' => false,
        ])
        ->and(firstlightSwitchPrimitiveProps($nodes['Validation error']))->toBe([
            'value' => false,
            'label' => 'Validation error',
            'helper' => '',
            'error' => 'Choose whether updates can be sent.',
            'disabled' => false,
        ])
        ->and(firstlightSwitchPrimitiveProps($nodes['Rejected setting']))->toBe([
            'value' => false,
            'label' => 'Rejected setting',
            'helper' => 'Every proposed value is rejected by the server.',
            'error' => '',
            'disabled' => false,
        ])
        ->and(firstlightSwitchPrimitiveProps($nodes['Programmatic setting']))->toBe([
            'value' => false,
            'label' => 'Programmatic setting',
            'helper' => 'Use the buttons below to change this setting on the server.',
            'error' => '',
            'disabled' => false,
        ])
        ->and(firstlightSwitchPrimitiveProps($nodes['A considerably longer setting label that wraps naturally']))->toBe([
            'value' => false,
            'label' => 'A considerably longer setting label that wraps naturally',
            'helper' => '',
            'error' => '',
            'disabled' => false,
        ]);

    $screen->assertAccessible();
});

it('dispatches accepted, rejected, disabled, and programmatic Switch changes through the native harness', function () {
    $screen = Native::visit('/components/switch');

    $screen->toggle('notificationsOff', true)
        ->assertSet('notificationsOff', true)
        ->toggle('notificationsOn', false)
        ->assertSet('notificationsOn', false);

    expect(fn () => $screen->toggle('disabledOff', true))
        ->toThrow('No callback registered');

    expect(fn () => $screen->toggle('disabledOn', false))
        ->toThrow('No callback registered');

    $screen->assertSet('disabledOff', false)
        ->assertSet('disabledOn', true);

    $previousRevision = $screen->tree()['props']['firstlight_response_revision'] ?? null;
    expect($previousRevision)->toBeInt();

    foreach ([true, false, true] as $attempt => $value) {
        $screen->toggle('rejectedSwitch', $value)
            ->assertSet('rejected', false)
            ->assertSet('rejectedAttempts', $attempt + 1);

        $revision = $screen->tree()['props']['firstlight_response_revision'];
        expect(firstlightSwitchNodesByLabel($screen->tree())['Rejected setting']['props']['value'])->toBeFalse()
            ->and($revision)->toBeGreaterThan($previousRevision);
        $previousRevision = $revision;
    }

    $screen->call('enableProgrammaticSwitch')
        ->assertSet('programmatic', true)
        ->call('resetProgrammaticSwitch')
        ->assertSet('programmatic', false);
});

it('publishes a Switch named only by a11y-label with an a11y-hint', function () {
    $screen = Native::visit('/components/switch');
    $node = firstlightSwitchNodesByLabel($screen->tree())['Appointment reminders'];

    expect(firstlightSwitchPrimitiveProps($node))->toBe([
        'a11y_label' => 'Appointment reminders',
        'a11y_hint' => 'Sends a reminder the day before each appointment',
        'value' => false,
        'label' => '',
        'helper' => '',
        'error' => '',
        'disabled' => false,
    ]);

    $screen->toggle('unlabelledReminders', true)->assertSet('unlabelledReminders', true);
});

it('auto-binds a failed accepted rule to the Switch and clears it when valid', function () {
    $screen = Native::visit('/components/switch');

    expect(firstlightSwitchNodesByLabel($screen->tree())['Share records']['props']['error'])->toBe('');

    $screen->toggle('shareRecords', false)->assertSet('shareRecords', false);

    expect(firstlightSwitchNodesByLabel($screen->tree())['Share records']['props'])->toMatchArray([
        'value' => false,
        'error' => 'Record sharing must stay on for this referral.',
    ]);

    $screen->toggle('shareRecords', true)->assertSet('shareRecords', true);

    expect(firstlightSwitchNodesByLabel($screen->tree())['Share records']['props']['error'])->toBe('');
});
