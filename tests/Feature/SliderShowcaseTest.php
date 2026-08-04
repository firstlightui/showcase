<?php

use Native\Mobile\Testing\Native;

/** @return array<string, array<string, mixed>> */
function firstlightSlidersByLabel(array $tree): array
{
    $nodes = [];
    if (($tree['type'] ?? null) === 'firstlight.slider') {
        $nodes[$tree['props']['label']] = $tree;
    }
    foreach ($tree['children'] ?? [] as $child) {
        $nodes = [...$nodes, ...firstlightSlidersByLabel($child)];
    }

    return $nodes;
}

it('publishes the complete Slider catalogue through the native wire tree', function () {
    $screen = Native::visit('/slider');
    $tree = $screen->tree();
    $nodes = firstlightSlidersByLabel($tree);

    expect($tree['type'])->toBe('native_root_stack')
        ->and($tree['props']['title'] ?? null)->toBe('Firstlight Slider')
        ->and(array_keys($nodes))->toBe([
            'Live dose',
            'Blur temperature offset',
            'Debounced ratio',
            'Dose with error',
            'Disabled dose',
            'Server-approved dose',
            'Programmatic dose',
        ])
        ->and($nodes['Live dose']['props'])->toMatchArray([
            'value' => 5.0,
            'min' => 0.0,
            'max' => 10.0,
            'step' => 1.0,
            'interval_count' => 10,
            'sync_mode' => 'live',
            'a11y_value' => '5 milligrams',
        ])
        ->and($nodes['Blur temperature offset']['props'])->toMatchArray([
            'value' => 0.25,
            'min' => -1.5,
            'max' => 1.5,
            'step' => 0.25,
            'interval_count' => 12,
            'sync_mode' => 'blur',
        ])
        ->and($nodes['Debounced ratio']['props'])->toMatchArray([
            'value' => 0.5,
            'step' => 0.1,
            'sync_mode' => 'debounce',
            'debounce_ms' => 500,
        ]);

    $screen->assertAccessible();
});

it('publishes error disabled rejection and programmatic fixtures', function () {
    $nodes = firstlightSlidersByLabel(Native::visit('/slider')->tree());

    expect($nodes['Dose with error']['props']['error'])->toBe('Dose needs clinical review.')
        ->and($nodes['Disabled dose']['props']['disabled'])->toBeTrue()
        ->and($nodes['Server-approved dose']['props']['value'])->toBe(4.0)
        ->and($nodes['Programmatic dose']['props']['a11y_hint'])->toBe('Use the button below to replace the published value');
});

it('updates synchronization fixtures rejects one proposal and applies programmatic publication', function () {
    Native::visit('/slider')
        ->slide('liveDose', 7.0)
        ->assertSet('liveDose', 7.0)
        ->slide('blurTemperature', 0.75)
        ->assertSet('blurTemperature', 0.75)
        ->slide('debouncedRatio', 0.8)
        ->assertSet('debouncedRatio', 0.8)
        ->slide('rejectedDose', 6.0)
        ->assertSet('approvedDose', 4.0)
        ->assertSet('rejectedAttempts', 1)
        ->press('publishHigherDose')
        ->assertSet('programmaticDose', 8.0);
});
