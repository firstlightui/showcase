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
    $screen->assertSet('saveCount', 0)->assertSet('saved', null);

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

    $screen->assertSet('saved', 'Thanks, Alex Rivera.')
        ->assertSee('accepted submissions 1');

    $screen->select('name', '')->press('save')
        ->assertSet('saved', null)->assertSet('saveCount', 1)
        ->tap('Reset')->assertSee('accepted submissions 0');
});

/** @return list<array<string, mixed>> */
function firstlightValidationNodes(array $tree, string $type): array
{
    $nodes = ($tree['type'] ?? null) === $type ? [$tree] : [];

    foreach ($tree['children'] ?? [] as $child) {
        $nodes = [...$nodes, ...firstlightValidationNodes($child, $type)];
    }

    return $nodes;
}

/** @return list<array<string, mixed>> */
function firstlightValidationAlerts(array $tree): array
{
    $alerts = isset($tree['props']['validation_alert']) ? [$tree['props']] : [];

    foreach ($tree['children'] ?? [] as $child) {
        $alerts = [...$alerts, ...firstlightValidationAlerts($child)];
    }

    return $alerts;
}

it('summarises a failed submit, announces it once and focuses the first invalid field', function () {
    $screen = Native::visit('/validation');

    expect(firstlightValidationNodes($screen->tree(), 'firstlight.callout'))->toBe([]);

    $screen->press('save');
    [$summary] = firstlightValidationNodes($screen->tree(), 'firstlight.callout');
    $fields = firstlightValidationFieldsByLabel($screen->tree());
    $announcement = 'There are 3 errors. The name field is required. The email field is required. The accepted field must be accepted.';

    expect($summary['props'])->toMatchArray([
        'tone' => 'danger',
        'message' => "There are 3 errors.\nThe name field is required.\nThe email field is required.\nThe accepted field must be accepted.",
        'validation_announcement' => $announcement,
    ])
        ->and($fields['Name']['props'])->toMatchArray([
            'validation_alert' => $summary['props']['validation_alert'],
            'validation_focus' => true,
        ])
        ->and($fields['Email']['props'])->not->toHaveKey('validation_alert');

    // A blur update that fails only the email announces only that message.
    $screen->select('email', 'not-an-email');
    $alerts = firstlightValidationAlerts($screen->tree());

    expect(array_column($alerts, 'validation_announcement'))->toBe(['The email field must be a valid email address.'])
        ->and(array_column($alerts, 'validation_focus'))->toBe([]);

    $screen->assertAccessible();
});

it('shows a form-level error thrown by the submit action without success feedback', function () {
    $screen = Native::visit('/validation')
        ->select('name', 'Alex Rivera')
        ->select('email', 'taken@example.com')
        ->toggle('accepted', true)
        ->press('save');

    $callouts = firstlightValidationNodes($screen->tree(), 'firstlight.callout');

    expect(array_column(array_column($callouts, 'props'), 'message'))->toBe([
        'An account already exists for this email. Sign in instead.',
        'An account already exists for this email. Sign in instead.',
    ])
        ->and(firstlightValidationFieldsByLabel($screen->tree())['Email']['props']['error'])->toBe('');
    $screen->assertSet('saveCount', 0)->assertSet('saved', null);
});
