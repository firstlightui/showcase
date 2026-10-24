<?php

use Native\Mobile\Testing\Native;

/** @return array<string, array<string, mixed>> */
function firstlightSteppersByLabel(array $tree): array
{
    $nodes = [];
    if (($tree['type'] ?? null) === 'firstlight.stepper') {
        $nodes[$tree['props']['label']] = $tree;
    }
    foreach ($tree['children'] ?? [] as $child) {
        $nodes = [...$nodes, ...firstlightSteppersByLabel($child)];
    }

    return $nodes;
}

it('publishes the complete Stepper catalogue through the native wire tree', function () {
    $screen = Native::visit('/components/stepper');
    $tree = $screen->tree();
    $nodes = firstlightSteppersByLabel($tree);

    expect($tree['type'])->toBe('native_root_tabs')
        ->and($tree['props']['nav_title'] ?? null)->toBe('Firstlight Stepper')
        ->and(array_keys($nodes))->toBe([
            'Quantity',
            'Fractional dose',
            'Minimum quantity',
            'Maximum quantity',
            'Quantity with error',
            'Disabled quantity',
            'Server-approved quantity',
            'Programmatic quantity',
        ])
        ->and($nodes['Quantity']['props'])->toMatchArray([
            'value' => 5,
            'min' => 0,
            'max' => 10,
            'step' => 1,
            'number_kind' => 'integer',
            'decrement_value' => 4,
            'increment_value' => 6,
        ])
        ->and($nodes['Fractional dose']['props'])->toMatchArray([
            'value' => 0.5,
            'min' => 0.0,
            'max' => 1.0,
            'step' => 0.25,
            'number_kind' => 'float',
            'decrement_value' => 0.25,
            'increment_value' => 0.75,
        ])
        ->and($nodes['Minimum quantity']['props']['can_decrement'])->toBeFalse()
        ->and($nodes['Maximum quantity']['props']['can_increment'])->toBeFalse();

    $screen->assertAccessible();
});

it('publishes error disabled rejection and programmatic fixtures', function () {
    $nodes = firstlightSteppersByLabel(Native::visit('/components/stepper')->tree());

    expect($nodes['Quantity with error']['props']['error'])->toBe('Quantity needs clinical review.')
        ->and($nodes['Disabled quantity']['props']['disabled'])->toBeTrue()
        ->and($nodes['Server-approved quantity']['props']['value'])->toBe(4)
        ->and($nodes['Programmatic quantity']['props']['a11y_hint'])->toBe('Use the button below to replace the published value');
});

it('dispatches integer float rejection and programmatic publication callbacks', function () {
    $screen = Native::visit('/components/stepper');
    $screen
        ->fireEvent('minimumQuantity', 1, [], ['on_increment'])
        ->assertSet('minimumQuantity', 1)
        ->assertSee('PHP accepted state: 1')
        ->fireEvent('maximumQuantity', 1, [], ['on_decrement'])
        ->assertSet('maximumQuantity', 9)
        ->fireEvent('errorQuantity', 1, [], ['on_increment'])
        ->assertSet('errorQuantity', 5)
        ->assertSee('PHP accepted state: 5')
        ->press('__syncProperty("quantity",6)')
        ->assertSet('quantity', 6)
        ->press('__syncProperty("fractionalDose",0.75)')
        ->assertSet('fractionalDose', 0.75)
        ->press('rejectQuantity(5)')
        ->assertSet('approvedQuantity', 4)
        ->assertSet('rejectedAttempts', 1)
        ->assertSee('Rejected attempts: 1')
        ->tap('Publish higher quantity')
        ->assertSet('programmaticQuantity', 8);

    $nodes = firstlightSteppersByLabel($screen->tree());
    expect($nodes['Minimum quantity']['props']['value'])->toBe(1)
        ->and($nodes['Minimum quantity']['props']['can_decrement'])->toBeTrue()
        ->and($nodes['Maximum quantity']['props']['value'])->toBe(9)
        ->and($nodes['Maximum quantity']['props']['can_increment'])->toBeTrue()
        ->and($nodes['Quantity with error']['props']['value'])->toBe(5);
});
