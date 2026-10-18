<?php

use Native\Mobile\Testing\Native;

/** @return array<string, array<string, mixed>> */
function firstlightTextFieldNodesByLabel(array $tree): array
{
    $nodes = [];

    if (($tree['type'] ?? null) === 'firstlight.text-field') {
        $nodes[$tree['props']['label']] = $tree;
    }

    foreach ($tree['children'] ?? [] as $child) {
        $nodes = [...$nodes, ...firstlightTextFieldNodesByLabel($child)];
    }

    return $nodes;
}

it('publishes the complete Text Field catalogue through the native wire tree', function () {
    $screen = Native::visit('/text-field');
    $tree = $screen->tree();
    $nodes = firstlightTextFieldNodesByLabel($tree);

    expect($tree['type'])->toBe('native_root_stack')
        ->and($tree['props']['title'] ?? null)->toBe('Firstlight Text Field')
        ->and(array_keys($nodes))->toBe([
            'Email',
            'Search referrals',
            'Password',
            'Patient ID',
            'Email with error',
            'Recipient',
        ]);

    $screen->assertAccessible();
});

it('publishes input policy, sync mode, icons, and semantic affordances', function () {
    $nodes = firstlightTextFieldNodesByLabel(Native::visit('/text-field')->tree());

    expect($nodes['Email']['props'])->toMatchArray([
        'value' => 'clinician@example.com',
        'required' => true,
        'keyboard' => 'email',
        'content_type' => 'email',
        'autocapitalize' => 'none',
        'autocorrect' => false,
        'submit_label' => 'next',
        'sync_mode' => 'live',
        'leading_icon' => 'mail',
        'clearable' => true,
    ])->and($nodes['Search referrals']['props'])->toMatchArray([
        'sync_mode' => 'debounce',
        'debounce_ms' => 500,
        'autocapitalize' => 'words',
        'submit_label' => 'search',
        'clearable' => true,
    ])->and($nodes['Password']['props'])->toMatchArray([
        'sync_mode' => 'blur',
        'content_type' => 'password',
        'secure' => true,
        'revealable' => true,
    ]);
});

it('publishes read-only, error, and authored trailing-action semantics', function () {
    $nodes = firstlightTextFieldNodesByLabel(Native::visit('/text-field')->tree());

    expect($nodes['Patient ID']['props'])->toMatchArray([
        'value' => 'PT-2048',
        'read_only' => true,
        'helper' => 'Select and copy this identifier.',
    ])->and($nodes['Email with error']['props'])->toMatchArray([
        'value' => 'invalid-address',
        'error' => 'Enter a valid email address.',
    ])->and($nodes['Recipient']['props'])->toMatchArray([
        'trailing_icon' => 'qr-code-scanner',
        'trailing_a11y_label' => 'Simulate recipient entry',
    ])->and($nodes['Recipient']['on_press'])->toBeInt();
});

it('updates bound values and dispatches the authored trailing action', function () {
    $screen = Native::visit('/text-field');

    $screen->select('email', 'updated@example.com')
        ->assertSet('email', 'updated@example.com')
        ->select('errorEmail', 'test@example.com')
        ->assertSet('errorEmail', 'test@example.com')
        ->assertSee('PHP accepted state: test@example.com')
        ->select('search', 'Updated search')
        ->assertSee('PHP accepted state: Updated search')
        ->select('password', 'secret-value')
        ->assertSee('PHP accepted password length: 12')
        ->tap('recipientAction')
        ->assertSet('scanRequests', 1)
        ->assertSet('recipient', 'PT-2048')
        ->assertSee('Simulated actions: 1')
        ->assertSee('PHP accepted state: PT-2048')
        ->tap('Reset email')
        ->assertSet('email', 'clinician@example.com');

    $nodes = firstlightTextFieldNodesByLabel($screen->tree());
    expect($nodes['Email with error']['props']['value'])->toBe('test@example.com')
        ->and($nodes['Recipient']['props']['value'])->toBe('PT-2048');
    // Inspect text nodes only: the secure field must still receive its wire value.
    $text = function (array $node) use (&$text): array {
        return [
            ...(($node['type'] ?? '') === 'text' ? [$node['props']['text'] ?? ''] : []),
            ...array_merge([], ...array_map($text, $node['children'] ?? [])),
        ];
    };
    expect(implode(' ', $text($screen->tree())))
        ->toContain('PHP accepted password length: 12')
        ->not->toContain('secret-value');
});
