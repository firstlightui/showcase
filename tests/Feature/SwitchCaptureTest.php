<?php

use Native\Mobile\Testing\Native;

/** @return list<array<string, mixed>> */
function switchCaptureNodes(array $tree): array
{
    $nodes = ($tree['type'] ?? null) === 'firstlight.switch' ? [$tree] : [];

    foreach ($tree['children'] ?? [] as $child) {
        $nodes = [...$nodes, ...switchCaptureNodes($child)];
    }

    return $nodes;
}

it('publishes the stable Switch documentation capture fixture', function () {
    $screen = Native::visit('/captures/switch');
    $nodes = switchCaptureNodes($screen->tree());

    expect($screen->tree()['props']['title'] ?? null)->toBe('Firstlight Switch')
        ->and($nodes)->toHaveCount(3)
        ->and(array_column(array_column($nodes, 'props'), 'label'))->toBe([
            'Notifications',
            'Automatic updates',
            'Setting with error',
        ])
        ->and($nodes[0]['props']['value'])->toBeFalse()
        ->and($nodes[1]['props']['value'])->toBeTrue()
        ->and($nodes[2]['props']['value'])->toBeFalse()
        ->and($nodes[0]['props']['helper'])->toBe('Receive account updates.')
        ->and($nodes[1]['props']['helper'])->toBe('Install updates automatically.')
        ->and($nodes[2]['props']['error'])->toBe('Choose whether this setting is enabled.')
        ->and($nodes[2]['props']['a11y_hint'])->toBe('Resolve the setting error');

    $screen->assertAccessible();
});
