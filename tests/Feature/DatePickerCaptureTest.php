<?php

use Native\Mobile\Testing\Native;

/** @return list<array<string, mixed>> */
function datePickerCaptureNodes(array $tree): array
{
    $nodes = ($tree['type'] ?? null) === 'firstlight.date-picker' ? [$tree] : [];
    foreach ($tree['children'] ?? [] as $child) {
        $nodes = [...$nodes, ...datePickerCaptureNodes($child)];
    }

    return $nodes;
}

it('publishes the stable Date Picker documentation capture fixture', function () {
    $screen = Native::visit('/captures/date-picker');
    $tree = $screen->tree();
    $nodes = datePickerCaptureNodes($tree);

    expect($tree['props']['title'] ?? null)->toBe('Firstlight Date Picker')
        ->and($nodes)->toHaveCount(4)
        ->and(array_column(array_column($nodes, 'props'), 'label'))->toBe([
            'Appointment date',
            'Review date',
            'Discharge date',
            'Disabled date',
        ])
        ->and($nodes[0]['props'])->toMatchArray([
            'has_value' => false,
            'value' => '',
            'locale' => 'en-AU',
            'timezone' => 'Australia/Sydney',
        ])
        ->and($nodes[1]['props'])->toMatchArray([
            'value' => '2026-08-04',
            'min' => '2026-08-01',
            'max' => '2026-08-31',
            'required' => true,
        ])
        ->and($nodes[2]['props']['error'])->toBe('Choose a discharge date.')
        ->and($nodes[3]['props']['disabled'])->toBeTrue();

    $screen->assertAccessible();
});
