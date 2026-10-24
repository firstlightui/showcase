<?php

use Native\Mobile\Testing\Native;

/** @return list<array<string, mixed>> */
function listSectionCaptureNodes(array $tree): array
{
    $nodes = ($tree['type'] ?? null) === 'list_section' ? [$tree] : [];

    foreach ($tree['children'] ?? [] as $child) {
        $nodes = [...$nodes, ...listSectionCaptureNodes($child)];
    }

    return $nodes;
}

it('publishes the stable List Section capture fixture', function () {
    $screen = Native::visit('/captures/list-section');
    $sections = listSectionCaptureNodes($screen->tree());

    expect($screen->tree()['props']['title'] ?? null)->toBe('Firstlight List Section')
        ->and($sections)->toHaveCount(3)
        ->and(array_map(fn (array $section): array => [
            $section['props']['header'] ?? null,
            $section['props']['footer'] ?? null,
            count($section['children'] ?? []),
        ], $sections))->toBe([
            ['Account', 'Signed in as Alex', 2],
            ['Notifications', null, 1],
            [null, 'Exports include every record you can access.', 1],
        ]);

    $screen->assertAccessible();
});
