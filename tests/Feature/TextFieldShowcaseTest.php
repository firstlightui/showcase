<?php

use Native\Mobile\Testing\Native;

/** @return array<string, array<string, mixed>> */
function firstlightTextFieldNodesByLabel(array $tree): array
{
    $nodes = [];

    if (($tree['type'] ?? null) === 'firstlight.text-field') {
        $nodes[$tree['props']['label'] ?: $tree['props']['a11y_label']] = $tree;
    }

    foreach ($tree['children'] ?? [] as $child) {
        $nodes = [...$nodes, ...firstlightTextFieldNodesByLabel($child)];
    }

    return $nodes;
}

it('publishes the complete Text Field catalogue through the native wire tree', function () {
    $screen = Native::visit('/components/text-field');
    $tree = $screen->tree();
    $nodes = firstlightTextFieldNodesByLabel($tree);

    expect($tree['type'])->toBe('native_root_tabs')
        ->and($tree['props']['nav_title'] ?? null)->toBe('Firstlight Text Field')
        ->and(array_keys($nodes))->toBe([
            'Checked identifier',
            'Review identifier',
            'Label identifier',
            'Email',
            'Card number',
            'Postal code',
            'Search referrals',
            'Password',
            'Patient ID',
            'Email with error',
            'Reference',
            'Note',
            'Lazy referral',
            'Live referral',
            'Server-approved ward',
            'Full name',
            'Username',
            'New password',
            'One-time code',
            'Phone',
            'Website',
            'Dose (mg)',
            'Ward code',
            'Handover note',
            'Recipient',
        ])
        ->and($nodes['Checked identifier']['props'])->toMatchArray(['helper' => 'Checksum OK', 'helper_tone' => 'success'])
        ->and($nodes['Review identifier']['props'])->toMatchArray(['helper' => 'Check this', 'helper_tone' => 'warning'])
        ->and($nodes['Label identifier']['props'])->toMatchArray(['helper' => 'From label']);

    $screen->assertAccessible();
});

it('publishes input policy, sync mode, icons, and semantic affordances', function () {
    $nodes = firstlightTextFieldNodesByLabel(Native::visit('/components/text-field')->tree());

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

it('publishes input masks without changing the bound values or keyboards', function () {
    $screen = Native::visit('/components/text-field');
    $nodes = firstlightTextFieldNodesByLabel($screen->tree());

    expect($nodes['Card number']['props'])->toMatchArray([
        'value' => '',
        'mask' => '#### #### #### ####',
        'keyboard' => 'number',
        'sync_mode' => 'live',
        'clearable' => true,
    ])->and($nodes['Postal code']['props'])->toMatchArray([
        'value' => 'K1A0B1',
        'mask' => 'A#A #A#',
        'autocapitalize' => 'characters',
        'sync_mode' => 'blur',
    ])->and($nodes['Postal code']['props'])->not->toHaveKey('keyboard');

    $screen->select('cardNumber', '4111111111111111')
        ->assertSet('cardNumber', '4111111111111111')
        ->assertSee('PHP accepted state: 4111111111111111');
});

it('publishes read-only, error, and authored trailing-action semantics', function () {
    $nodes = firstlightTextFieldNodesByLabel(Native::visit('/components/text-field')->tree());

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
    $screen = Native::visit('/components/text-field');

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

it('toggles disabled from PHP and dispatches keyboard submit with the current text', function () {
    $screen = Native::visit('/components/text-field');
    $nodes = firstlightTextFieldNodesByLabel($screen->tree());

    expect($nodes['Reference']['props'])->toMatchArray([
        'value' => 'REF-7731',
        'disabled' => true,
        'clearable' => true,
    ])->and($nodes['Note']['props'])->toMatchArray([
        'sync_mode' => 'blur',
        'submit_label' => 'send',
    ])->and($nodes['Note']['props']['on_submit'])->toBeInt();

    $screen->tap('Enable reference')
        ->assertSet('referenceDisabled', false)
        ->assertSee('PHP disabled: no')
        ->submit('noteField', 'Bed 4 reviewed')
        ->assertSet('lastSubmittedNote', 'Bed 4 reviewed')
        ->assertSee('Last submitted: Bed 4 reviewed; submissions: 1')
        ->submit('noteField')
        ->assertSee('Last submitted: (empty); submissions: 2');

    expect(firstlightTextFieldNodesByLabel($screen->tree())['Reference']['props']['disabled'])->toBeFalse();

    $screen->tap('Disable reference')->assertSet('referenceDisabled', true);
    expect(firstlightTextFieldNodesByLabel($screen->tree())['Reference']['props']['disabled'])->toBeTrue();
});

it('publishes lazy and live models and keeps a rejected edit at the approved value', function () {
    $screen = Native::visit('/components/text-field');
    $nodes = firstlightTextFieldNodesByLabel($screen->tree());

    expect($nodes['Lazy referral']['props']['sync_mode'])->toBe('blur')
        ->and($nodes['Live referral']['props']['sync_mode'])->toBe('live')
        ->and($nodes['Server-approved ward']['props'])->toMatchArray(['value' => 'Ward 4', 'sync_mode' => 'live']);

    $screen->select('lazyReferral', 'Dermatology')
        ->assertSee('PHP accepted state: Dermatology (lazy)')
        ->select('liveReferral', 'Oncology')
        ->assertSee('PHP accepted state: Oncology (live)')
        ->select('rejectWard', 'Ward 9')
        ->assertSet('approvedWard', 'Ward 4')
        ->assertSet('rejectedWardEdits', 1)
        ->assertSee('PHP accepted state: Ward 4; Rejected edits: 1');

    expect(firstlightTextFieldNodesByLabel($screen->tree())['Server-approved ward']['props']['value'])->toBe('Ward 4');
});

it('publishes every documented keyboard and content type', function () {
    $nodes = firstlightTextFieldNodesByLabel(Native::visit('/components/text-field')->tree());

    expect($nodes['Full name']['props'])->toMatchArray(['content_type' => 'name'])
        ->and($nodes['Username']['props'])->toMatchArray(['content_type' => 'username'])
        ->and($nodes['New password']['props'])->toMatchArray(['content_type' => 'new-password', 'secure' => true])
        ->and($nodes['One-time code']['props'])->toMatchArray(['content_type' => 'one-time-code', 'keyboard' => 'number'])
        ->and($nodes['Phone']['props'])->toMatchArray(['keyboard' => 'phone'])
        ->and($nodes['Website']['props'])->toMatchArray(['keyboard' => 'url'])
        ->and($nodes['Dose (mg)']['props'])->toMatchArray(['keyboard' => 'decimal']);
});

it('names a label-less field by a11y-label and records the PHP value at a button press', function () {
    $screen = Native::visit('/components/text-field');
    $nodes = firstlightTextFieldNodesByLabel($screen->tree());

    expect($nodes['Ward code']['props'])->toMatchArray([
        'label' => '',
        'a11y_label' => 'Ward code',
        'a11y_hint' => 'Four characters, for example W4AB',
    ])->and($nodes['Handover note']['props']['sync_mode'])->toBe('blur');

    $screen->select('wardCode', 'W4AB')
        ->assertSee('PHP accepted state: W4AB')
        ->select('flushNote', 'Stable overnight')
        ->tap('Record PHP value')
        ->assertSet('flushPresses', 1)
        ->assertSee('PHP value at press: Stable overnight; presses: 1');
});
