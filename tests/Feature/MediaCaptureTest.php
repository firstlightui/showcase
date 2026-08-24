<?php

use Native\Mobile\Testing\Native;

/** @return list<array<string, mixed>> */
function mediaCaptureNodes(array $tree): array
{
    $nodes = ($tree['type'] ?? null) === 'firstlight.media' ? [$tree] : [];

    foreach ($tree['children'] ?? [] as $child) {
        $nodes = [...$nodes, ...mediaCaptureNodes($child)];
    }

    return $nodes;
}

it('publishes the stable Media documentation capture fixture', function () {
    $screen = Native::visit('/captures/media');
    $nodes = mediaCaptureNodes($screen->tree());

    expect($screen->tree()['props']['title'] ?? null)->toBe('Firstlight Media')
        ->and($nodes)->toHaveCount(4)
        ->and(array_column(array_column($nodes, 'props'), 'label'))->toBe([
            'Profile photo',
            'Contract',
            'Locked photo',
            'Attachment',
        ])
        ->and($nodes[0]['props'])->toMatchArray([
            'mode' => 'image',
            'required' => true,
            'aspect' => '1:1',
            'crop' => 'required',
            'has_value' => true,
            'path' => 'avatars/profile.jpg',
            'helper' => 'Square crop required',
        ])
        ->and($nodes[1]['props'])->toMatchArray([
            'mode' => 'document',
            'has_value' => true,
            'path' => 'docs/contract.pdf',
            'mime' => 'application/pdf',
        ])
        ->and($nodes[1]['props'])->not->toHaveKeys(['aspect', 'crop'])
        ->and($nodes[2]['props'])->toMatchArray([
            'disabled' => true,
            'has_value' => true,
        ])
        ->and($nodes[3]['props'])->toMatchArray([
            'mode' => 'image',
            'crop' => 'optional',
            'has_value' => false,
            'required' => true,
            'error' => 'Choose a clearer photo.',
            'a11y_hint' => 'Resolve the attachment error',
        ]);

    $screen->assertAccessible();
});
