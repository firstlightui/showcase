<?php

use Native\Mobile\Testing\Native;

/** @return list<array<string, mixed>> */
function customIconsShowcaseNodes(array $tree, string $type): array
{
    $nodes = ($tree['type'] ?? null) === $type ? [$tree] : [];

    foreach ($tree['children'] ?? [] as $child) {
        $nodes = [...$nodes, ...customIconsShowcaseNodes($child, $type)];
    }

    return $nodes;
}

it('draws its installed SVGs as custom icons across Firstlight controls', function () {
    $screen = Native::visit('/components/custom-icons')->assertAccessible();
    $tree = $screen->tree();
    $icon = fn (string $type, string $prop): array => array_column(array_column(customIconsShowcaseNodes($tree, $type), 'props'), $prop);

    expect($tree['props']['nav_title'] ?? null)->toBe('Custom Icons')
        ->and($icon('firstlight.icon-button', 'icon'))->toBe(['custom:rocket-launch-solid', 'custom:mug-hot', 'custom:git-commit-vertical', 'plus'])
        ->and($icon('firstlight.list-item', 'leading_value'))->toContain('custom:plant-2', 'custom:bell')
        ->and($icon('firstlight.text-field', 'leading_icon'))->toBe(['custom:git-commit-vertical'])
        ->and($icon('firstlight.status-label', 'icon'))->toBe(['custom:rocket-launch-solid']);

    foreach (['rocket-launch-solid', 'mug-hot', 'git-commit-vertical', 'plant-2', 'paw-print-fill', 'nutrition-outline'] as $name) {
        expect(resource_path("icons/{$name}.svg"))->toBeFile();
    }

    // The fallback row only demonstrates its system icon while bell has no SVG.
    expect(resource_path('icons/bell.svg'))->not->toBeFile();

    $screen->tap('Launch')->assertSee('Last action: Launch');
});
