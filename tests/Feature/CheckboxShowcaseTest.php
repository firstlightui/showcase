<?php

use Native\Mobile\Testing\Native;

/** @return array<string, array<string, mixed>> */
function firstlightCheckboxNodesByLabel(array $tree): array
{
    $nodes = [];

    if (($tree['type'] ?? null) === 'firstlight.checkbox') {
        $nodes[$tree['props']['label']] = $tree;
    }

    foreach ($tree['children'] ?? [] as $child) {
        $nodes = [...$nodes, ...firstlightCheckboxNodesByLabel($child)];
    }

    return $nodes;
}

/** @return array<string, bool|string> */
function firstlightCheckboxPrimitiveProps(array $node): array
{
    return array_diff_key($node['props'], array_flip(['on_change']));
}

it('publishes the complete Checkbox catalogue through the native wire tree', function () {
    $screen = Native::visit('/checkbox');
    $nodes = firstlightCheckboxNodesByLabel($screen->tree());

    expect($screen->tree()['props']['title'] ?? null)->toBe('Firstlight Checkbox')
        ->and(array_keys($nodes))->toBe([
            'Unchecked',
            'Checked and required',
            'Disabled unchecked',
            'Disabled checked',
            'Helper text',
            'Validation error',
            'Rejected agreement',
            'Programmatic agreement',
            'A considerably longer agreement label that wraps naturally across multiple lines',
        ])
        ->and(firstlightCheckboxPrimitiveProps($nodes['Unchecked']))->toBe([
            'value' => false,
            'label' => 'Unchecked',
            'helper' => '',
            'error' => '',
            'required' => false,
            'disabled' => false,
        ])
        ->and(firstlightCheckboxPrimitiveProps($nodes['Checked and required']))->toBe([
            'value' => true,
            'label' => 'Checked and required',
            'helper' => '',
            'error' => '',
            'required' => true,
            'disabled' => false,
        ])
        ->and(firstlightCheckboxPrimitiveProps($nodes['Validation error']))->toMatchArray([
            'value' => false,
            'error' => 'Agreement is required before continuing.',
            'required' => true,
            'disabled' => false,
            'a11y_hint' => 'Resolve the agreement error',
        ]);

    $screen->assertAccessible();
});

it('dispatches accepted rejected disabled and programmatic Checkbox changes', function () {
    $screen = Native::visit('/checkbox');

    $screen->toggle('unchecked', true)
        ->assertSet('unchecked', true)
        ->toggle('checkedRequired', false)
        ->assertSet('checkedRequired', false);

    expect(fn () => $screen->toggle('disabledUnchecked', true))
        ->toThrow('No callback registered');
    expect(fn () => $screen->toggle('disabledChecked', false))
        ->toThrow('No callback registered');

    foreach ([true, false, true] as $attempt => $value) {
        $screen->toggle('rejectedCheckbox', $value)
            ->assertSet('rejected', false)
            ->assertSet('rejectedAttempts', $attempt + 1);

        expect(firstlightCheckboxNodesByLabel($screen->tree())['Rejected agreement']['props']['value'])
            ->toBeFalse();
    }

    $screen->call('acceptProgrammaticAgreement')
        ->assertSet('programmatic', true)
        ->call('resetProgrammaticAgreement')
        ->assertSet('programmatic', false);
});
