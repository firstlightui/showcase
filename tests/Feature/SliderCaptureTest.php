<?php

use Native\Mobile\Testing\Native;

/** @return list<array<string, mixed>> */
function sliderCaptureNodes(array $tree): array
{
    $nodes = ($tree['type'] ?? null) === 'firstlight.slider' ? [$tree] : [];
    foreach ($tree['children'] ?? [] as $child) {
        $nodes = [...$nodes, ...sliderCaptureNodes($child)];
    }

    return $nodes;
}

it('publishes the stable Slider documentation capture fixture', function () {
    $screen = Native::visit('/captures/slider');
    $tree = $screen->tree();
    $nodes = sliderCaptureNodes($tree);

    expect($tree['props']['title'] ?? null)->toBe('Firstlight Slider')
        ->and($nodes)->toHaveCount(4)
        ->and(array_column(array_column($nodes, 'props'), 'label'))->toBe([
            'Medication dose',
            'Temperature offset',
            'Dose with error',
            'Disabled dose',
        ])
        ->and($nodes[0]['props'])->toMatchArray([
            'value' => 5.0,
            'interval_count' => 10,
            'a11y_value' => '5 milligrams',
        ])
        ->and($nodes[1]['props'])->toMatchArray([
            'value' => 0.25,
            'min' => -1.5,
            'max' => 1.5,
            'step' => 0.25,
            'interval_count' => 12,
        ])
        ->and($nodes[2]['props']['error'])->toBe('Dose needs clinical review.')
        ->and($nodes[3]['props']['disabled'])->toBeTrue();

    $screen->assertAccessible();
});
