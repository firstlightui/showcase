<?php

use Native\Mobile\Testing\Native;

/** @return list<array<string, mixed>> */
function codeEditorCaptureNodes(array $tree): array
{
    $nodes = ($tree['type'] ?? null) === 'firstlight.code-editor' ? [$tree] : [];
    foreach ($tree['children'] ?? [] as $child) {
        $nodes = [...$nodes, ...codeEditorCaptureNodes($child)];
    }

    return $nodes;
}

it('publishes the stable Code Editor documentation capture fixture', function () {
    $screen = Native::visit('/captures/code-editor');
    $tree = $screen->tree();
    $nodes = codeEditorCaptureNodes($tree);

    expect($tree['props']['title'] ?? null)->toBe('Firstlight Code Editor')
        ->and($nodes)->toHaveCount(3)
        ->and(array_column(array_column($nodes, 'props'), 'label'))->toBe([
            'Webhook payload',
            'Environment',
            'Deploy workflow',
        ])
        ->and(array_column(array_column($nodes, 'props'), 'language'))->toBe(['json', 'env', 'yaml'])
        ->and($nodes[1]['props']['error'])->toBe('APP_KEY is missing.')
        ->and($nodes[2]['props']['read_only'])->toBeTrue()
        ->and($nodes[2]['props']['value'])->toBe("deploy:\n  branch: main\n  steps:\n    - composer install");

    $screen->assertAccessible();
});
