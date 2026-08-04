<?php

use Native\Mobile\Testing\Native;

/** @return list<array<string, mixed>> */
function textFieldCaptureNodes(array $tree): array
{
    $nodes = ($tree['type'] ?? null) === 'firstlight.text-field' ? [$tree] : [];

    foreach ($tree['children'] ?? [] as $child) {
        $nodes = [...$nodes, ...textFieldCaptureNodes($child)];
    }

    return $nodes;
}

it('publishes the stable Text Field documentation capture fixture', function () {
    $screen = Native::visit('/captures/text-field');
    $tree = $screen->tree();
    $nodes = textFieldCaptureNodes($tree);

    expect($tree['props']['title'] ?? null)->toBe('Firstlight Text Field')
        ->and($nodes)->toHaveCount(3)
        ->and(array_column(array_column($nodes, 'props'), 'label'))->toBe([
            'Email',
            'Password',
            'Email with error',
        ])
        ->and($nodes[0]['props'])->toMatchArray([
            'value' => 'clinician@example.com',
            'keyboard' => 'email',
            'content_type' => 'email',
            'leading_icon' => 'mail',
            'clearable' => true,
        ])
        ->and($nodes[1]['props'])->toMatchArray([
            'secure' => true,
            'revealable' => true,
            'sync_mode' => 'blur',
        ])
        ->and($nodes[2]['props']['error'])->toBe('Enter a valid email address.');

    $screen->assertAccessible();
});
