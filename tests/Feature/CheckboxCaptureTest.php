<?php

use Native\Mobile\Testing\Native;

/** @return list<array<string, mixed>> */
function checkboxCaptureNodes(array $tree): array
{
    $nodes = ($tree['type'] ?? null) === 'firstlight.checkbox' ? [$tree] : [];

    foreach ($tree['children'] ?? [] as $child) {
        $nodes = [...$nodes, ...checkboxCaptureNodes($child)];
    }

    return $nodes;
}

it('publishes the stable Checkbox documentation capture fixture', function () {
    $screen = Native::visit('/captures/checkbox');
    $nodes = checkboxCaptureNodes($screen->tree());

    expect($screen->tree()['props']['title'] ?? null)->toBe('Firstlight Checkbox')
        ->and($nodes)->toHaveCount(4)
        ->and(array_column(array_column($nodes, 'props'), 'label'))->toBe([
            'I agree to the terms',
            'Share anonymous diagnostics',
            'Disabled agreement',
            'Agreement with error',
        ])
        ->and($nodes[0]['props'])->toMatchArray([
            'value' => false,
            'required' => true,
            'helper' => 'Required before continuing.',
        ])
        ->and($nodes[1]['props']['value'])->toBeTrue()
        ->and($nodes[2]['props'])->toMatchArray([
            'value' => true,
            'disabled' => true,
        ])
        ->and($nodes[3]['props'])->toMatchArray([
            'value' => false,
            'required' => true,
            'error' => 'Agreement is required before continuing.',
            'a11y_hint' => 'Resolve the agreement error',
        ]);

    $screen->assertAccessible();
});
