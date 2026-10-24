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
    $screen = Native::visit('/components/text-area');
    $tree = $screen->tree();
    $nodes = firstlightTextAreasByLabel($tree);

    expect($tree['type'])->toBe('native_root_tabs')
        ->and($tree['props']['nav_title'] ?? null)->toBe('Firstlight Text Area')
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
    $nodes = firstlightTextAreasByLabel(Native::visit('/components/text-area')->tree());
    expect($nodes['Note with error']['props']['error'])->toBe('Add at least one observation.')
        ->and($nodes['Read-only note']['props']['read_only'])->toBeTrue()
        ->and($nodes['Disabled note']['props']['disabled'])->toBeTrue()
        ->and($nodes['Server-approved note']['props']['value'])->toBe('Approved clinical wording.')
        ->and($nodes['Programmatic note']['props']['a11y_hint'])->toBe('Use the buttons below to replace the published value');
});

it('updates bound text rejects one proposal and applies programmatic publications', function () {
    $screen = Native::visit('/components/text-area');
    $screen
        ->select('errorNotes', 'Complete note')
        ->assertSet('errorNotes', 'Complete note')
        ->assertSee('PHP accepted state: Complete note')
        ->select('handover', 'Next shift')
        ->assertSee('PHP accepted state: Next shift')
        ->select('summary', 'Updated summary')
        ->assertSee('PHP accepted state: Updated summary')
        ->select('notes', "Updated history\nUpdated plan")
        ->assertSet('notes', "Updated history\nUpdated plan")
        ->select('rejectedNotes', 'Unapproved wording')
        ->assertSet('approved', 'Approved clinical wording.')
        ->assertSet('rejectedAttempts', 1)
        ->assertSee('Rejected attempts: 1')
        ->tap('Replace note')
        ->assertSet('programmatic', "Programmatic update.\nSecond line retained.")
        ->tap('Reset note')
        ->assertSet('programmatic', 'Initial programmatic note.');

    $nodes = firstlightTextAreasByLabel($screen->tree());
    expect($nodes['Note with error']['props']['value'])->toBe('Complete note')
        ->and($nodes['Handover note']['props']['value'])->toBe('Next shift')
        ->and($nodes['Debounced summary']['props']['value'])->toBe('Updated summary');
});
