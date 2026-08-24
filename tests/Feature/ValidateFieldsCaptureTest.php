<?php

use Native\Mobile\Testing\Native;

/** @return array<string, array<string, mixed>> */
function validateFieldsCaptureByLabel(array $tree): array
{
    $nodes = [];

    if (in_array($tree['type'] ?? null, ['firstlight.text-field', 'firstlight.checkbox'], true)) {
        $nodes[$tree['props']['label']] = $tree;
    }

    foreach ($tree['children'] ?? [] as $child) {
        $nodes = [...$nodes, ...validateFieldsCaptureByLabel($child)];
    }

    return $nodes;
}

it('publishes the stable ValidatesFields documentation capture fixture', function () {
    $screen = Native::visit('/captures/validate-fields');
    $fields = validateFieldsCaptureByLabel($screen->tree());

    expect($screen->tree()['props']['title'] ?? null)->toBe('Firstlight Validate Fields')
        ->and(array_keys($fields))->toBe(['Name', 'Email', 'I agree to the terms'])
        ->and($fields['Name']['props'])->toMatchArray([
            'value' => '',
            'error' => 'The name field is required.',
        ])
        ->and($fields['Email']['props'])->toMatchArray([
            'value' => 'not-an-email',
            'keyboard' => 'email',
            'error' => 'The email must be a valid email address.',
        ])
        ->and($fields['I agree to the terms']['props'])->toMatchArray([
            'value' => false,
            'error' => 'The terms must be accepted.',
        ]);

    $screen->assertAccessible();
});
