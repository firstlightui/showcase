<?php

use Native\Mobile\Testing\Native;

/** @return array<string, array<string, mixed>> */
function firstlightValidationFieldsByLabel(array $tree): array
{
    $nodes = [];

    if (in_array($tree['type'] ?? null, ['firstlight.text-field', 'firstlight.checkbox'], true)) {
        $nodes[$tree['props']['label']] = $tree;
    }

    foreach ($tree['children'] ?? [] as $child) {
        $nodes = [...$nodes, ...firstlightValidationFieldsByLabel($child)];
    }

    return $nodes;
}

it('publishes an empty signup form without authored field errors', function () {
    $screen = Native::visit('/validation');
    $fields = firstlightValidationFieldsByLabel($screen->tree());

    expect($screen->tree()['props']['title'] ?? null)->toBe('Firstlight Validation')
        ->and(array_keys($fields))->toBe(['Name', 'Email', 'I agree to the terms'])
        ->and($fields['Name']['props']['error'])->toBe('')
        ->and($fields['Email']['props']['error'])->toBe('')
        ->and($fields['I agree to the terms']['props']['error'])->toBe('');

    $screen->assertAccessible();
});

it('fails real Laravel rules then passes and clears field errors', function () {
    $screen = Native::visit('/validation');

    $screen->press('save');
    $failed = firstlightValidationFieldsByLabel($screen->tree());

    expect($failed['Name']['props']['error'])->not->toBe('')
        ->and($failed['Email']['props']['error'])->not->toBe('')
        ->and($failed['I agree to the terms']['props']['error'])->not->toBe('');

    $screen->select('name', 'Al')
        ->select('email', 'not-an-email');

    $partial = firstlightValidationFieldsByLabel($screen->tree());

    expect($partial['Email']['props']['error'])->not->toBe('');

    $screen->select('name', 'Alex Rivera')
        ->select('email', 'alex@example.com')
        ->toggle('accepted', true)
        ->press('save');

    $passed = firstlightValidationFieldsByLabel($screen->tree());

    expect($passed['Name']['props']['error'])->toBe('')
        ->and($passed['Email']['props']['error'])->toBe('')
        ->and($passed['I agree to the terms']['props']['error'])->toBe('');

    $screen->assertSet('saved', 'Thanks, Alex Rivera.');
});
