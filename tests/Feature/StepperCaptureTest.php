<?php

use Native\Mobile\Testing\Native;

/** @return list<array<string, mixed>> */
function stepperCaptureNodes(array $tree): array
{
    $nodes = ($tree['type'] ?? null) === 'firstlight.stepper' ? [$tree] : [];
    foreach ($tree['children'] ?? [] as $child) {
        $nodes = [...$nodes, ...stepperCaptureNodes($child)];
    }

    return $nodes;
}

it('publishes the stable Stepper documentation capture fixture', function () {
    $screen = Native::visit('/captures/stepper');
    $tree = $screen->tree();
    $nodes = stepperCaptureNodes($tree);

    expect($tree['props']['title'] ?? null)->toBe('Firstlight Stepper')
        ->and($nodes)->toHaveCount(4)
        ->and(array_column(array_column($nodes, 'props'), 'label'))->toBe([
            'Medication quantity',
            'Fractional dose',
            'Quantity with error',
            'Disabled quantity',
        ])
        ->and($nodes[0]['props'])->toMatchArray([
            'value' => 5,
            'number_kind' => 'integer',
            'decrement_value' => 4,
            'increment_value' => 6,
        ])
        ->and($nodes[1]['props'])->toMatchArray([
            'value' => 0.5,
            'min' => 0.0,
            'max' => 1.0,
            'step' => 0.25,
            'number_kind' => 'float',
        ])
        ->and($nodes[2]['props']['error'])->toBe('Quantity needs clinical review.')
        ->and($nodes[3]['props']['disabled'])->toBeTrue();

    $screen->assertAccessible();
});
