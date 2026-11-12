<?php

use Native\Mobile\Testing\Native;

/** @return list<array<string, mixed>> */
function progressShowcaseNodes(array $tree): array
{
    $nodes = ($tree['type'] ?? null) === 'firstlight.progress' ? [$tree] : [];

    foreach ($tree['children'] ?? [] as $child) {
        $nodes = [...$nodes, ...progressShowcaseNodes($child)];
    }

    return $nodes;
}

it('publishes the complete Progress gallery through the native wire tree', function () {
    $screen = Native::visit('/components/progress');
    $nodes = progressShowcaseNodes($screen->tree());

    expect($screen->tree()['props']['nav_title'] ?? null)->toBe('Firstlight Progress')
        ->and(count($nodes))->toBe(9)
        ->and(array_column(array_column($nodes, 'props'), 'a11y_label'))->toBe([
            'Upload not started',
            'Uploading documents',
            'Upload nearly complete',
            'Upload complete',
            'Preparing documents',
            'Checking document status',
            'Synchronising the complete historical document archive for offline access',
            'Interactive upload progress',
            'Importing patient records',
        ])
        ->and(array_column(array_slice(array_column($nodes, 'props'), 0, 4), 'value'))->toBe([
            0.0,
            0.42,
            0.87,
            1.0,
        ])
        ->and($nodes[4]['props']['indeterminate'])->toBeTrue()
        ->and($nodes[5]['props']['indeterminate'])->toBeTrue()
        ->and($nodes[6]['props']['indeterminate'])->toBeTrue()
        ->and($nodes[7]['props']['value'])->toBe(0.25);

    $screen->assertAccessible();
});

it('publishes programmatic progress changes without a Progress callback', function () {
    $screen = Native::visit('/components/progress');

    $screen->tap('Advance progress')->assertSet('uploadProgress', 0.5);
    $screen->tap('Advance progress')->assertSet('uploadProgress', 0.75);
    $screen->tap('Reset progress')->assertSet('uploadProgress', 0.25);

    $nodes = progressShowcaseNodes($screen->tree());

    expect($nodes[7]['props']['value'])->toBe(0.25)
        ->and($nodes[7]['props'])->not->toHaveKeys(['on_change', 'on_press']);
});

it('switches the same Progress node between indeterminate and determinate', function () {
    $screen = Native::visit('/components/progress');
    $import = fn () => progressShowcaseNodes($screen->tree())[8];

    $initial = $import();
    expect($initial['props']['indeterminate'])->toBeTrue()
        ->and($initial['props']['value'] ?? null)->toBeNull();
    $screen->assertSee('PHP accepted state: indeterminate (null)');

    $screen->tap('Next import step')->assertSet('importProgress', 0.0)
        ->assertSee('PHP accepted state: determinate 0%');
    expect($import()['props']['indeterminate'] ?? false)->toBeFalse()
        ->and($import()['props']['value'])->toBe(0.0)
        ->and($import()['id'])->toBe($initial['id']);

    $screen->tap('Next import step')->tap('Next import step')->assertSet('importProgress', 1.0);
    expect($import()['props']['value'])->toBe(1.0);

    $screen->tap('Next import step')->assertSet('importProgress', null);
    expect($import()['props']['indeterminate'])->toBeTrue()
        ->and($import()['id'])->toBe($initial['id'])
        ->and($import()['props'])->not->toHaveKeys(['on_change', 'on_press']);

    $screen->assertAccessible();
});
