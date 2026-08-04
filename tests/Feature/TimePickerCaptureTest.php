<?php

use Native\Mobile\Testing\Native;

/** @return list<array<string, mixed>> */
function timePickerCaptureNodes(array $tree): array
{
    $nodes = ($tree['type'] ?? null) === 'firstlight.time-picker' ? [$tree] : [];
    foreach ($tree['children'] ?? [] as $child) {
        $nodes = [...$nodes, ...timePickerCaptureNodes($child)];
    }

    return $nodes;
}

it('publishes the stable Time Picker documentation capture fixture', function () {
    $screen = Native::visit('/captures/time-picker');
    $tree = $screen->tree();
    $nodes = timePickerCaptureNodes($tree);

    expect($tree['props']['title'] ?? null)->toBe('Firstlight Time Picker')
        ->and($nodes)->toHaveCount(4)
        ->and(array_column(array_column($nodes, 'props'), 'label'))->toBe([
            'Appointment time',
            'Review time',
            'Arrival time',
            'Disabled time',
        ])
        ->and($nodes[0]['props'])->toMatchArray([
            'has_value' => false,
            'value' => '',
            'locale' => 'en-AU',
            'timezone' => 'Australia/Sydney',
        ])
        ->and($nodes[1]['props'])->toMatchArray([
            'value' => '14:30',
            'required' => true,
        ])
        ->and($nodes[2]['props']['error'])->toBe('Choose an arrival time.')
        ->and($nodes[3]['props']['disabled'])->toBeTrue();

    $screen->assertAccessible();
});
