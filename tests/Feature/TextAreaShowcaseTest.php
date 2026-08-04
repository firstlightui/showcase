<?php

use Native\Mobile\Testing\Native;

/** @return array<string, array<string, mixed>> */
function firstlightTextAreasByLabel(array $tree): array
{
    $nodes = [];
    if (($tree['type'] ?? null) === 'firstlight.text-area') {
        $nodes[$tree['props']['label']] = $tree;
    }
    foreach ($tree['children'] ?? [] as $child) {
        $nodes = [...$nodes, ...firstlightTextAreasByLabel($child)];
    }
    return $nodes;
}

it('publishes the complete Text Area catalogue through the native wire tree', function () {
    $screen = Native::visit('/text-area');
    $tree = $screen->tree();
    $nodes = firstlightTextAreasByLabel($tree);

    expect($tree['type'])->toBe('native_root_stack')
        ->and($tree['props']['title'] ?? null)->toBe('Firstlight Text Area')
        ->and(array_keys($nodes))->toBe([
            'Clinical notes',
            'Handover note',
            'Debounced summary',
            'Note with error',
            'Read-only note',
            'Disabled note',
            'Server-approved note',
            'Programmatic note',
        ])
        ->and($nodes['Clinical notes']['props'])->toMatchArray([
            'value' => "Patient reports improved sleep.\nNo new adverse effects.",
            'required' => true,
            'min_lines' => 4,
            'max_lines' => 8,
            'autocapitalize' => 'sentences',
            'autocorrect_policy' => 'enabled',
            'sync_mode' => 'live',
        ])
        ->and($nodes['Handover note']['props']['sync_mode'])->toBe('blur')
        ->and($nodes['Debounced summary']['props'])->toMatchArray([
            'sync_mode' => 'debounce',
            'debounce_ms' => 500,
            'min_lines' => 3,
            'max_lines' => 5,
        ]);

    $screen->assertAccessible();
});

it('publishes validation disabled read-only and reconciliation fixtures', function () {
    $nodes = firstlightTextAreasByLabel(Native::visit('/text-area')->tree());
    expect($nodes['Note with error']['props']['error'])->toBe('Add at least one observation.')
        ->and($nodes['Read-only note']['props']['read_only'])->toBeTrue()
        ->and($nodes['Disabled note']['props']['disabled'])->toBeTrue()
        ->and($nodes['Server-approved note']['props']['value'])->toBe('Approved clinical wording.')
        ->and($nodes['Programmatic note']['props']['a11y_hint'])->toBe('Use the buttons below to replace the published value');
});

it('updates bound text rejects one proposal and applies programmatic publications', function () {
    Native::visit('/text-area')
        ->select('notes', "Updated history\nUpdated plan")
        ->assertSet('notes', "Updated history\nUpdated plan")
        ->select('rejectedNotes', 'Unapproved wording')
        ->assertSet('approved', 'Approved clinical wording.')
        ->assertSet('rejectedAttempts', 1)
        ->press('replaceProgrammaticNotes')
        ->assertSet('programmatic', "Programmatic update.\nSecond line retained.")
        ->press('resetProgrammaticNotes')
        ->assertSet('programmatic', 'Initial programmatic note.');
});
