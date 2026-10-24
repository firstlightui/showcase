<?php

use Native\Mobile\Edge\ChromeContributorRegistry;
use Native\Mobile\Testing\Native;
use Native\Mobile\UI\NativeUIServiceProvider;

beforeEach(function () {
    ChromeContributorRegistry::reset();
    app()->getProvider(NativeUIServiceProvider::class)->boot();
});

/** @return list<array<string, mixed>> */
function showcaseAppearanceNodes(array $tree, string $type): array
{
    $nodes = ($tree['type'] ?? null) === $type ? [$tree] : [];

    foreach ($tree['children'] ?? [] as $child) {
        $nodes = [...$nodes, ...showcaseAppearanceNodes($child, $type)];
    }

    return $nodes;
}

it('follows the system appearance with the configured light and dark palettes', function () {
    $config = require config_path('native-ui.php');

    foreach (['/', '/gallery/inbox', '/recipes', '/components', '/components/button', '/components/media', '/captures/button'] as $path) {
        Native::visit($path);

        // Screens must never copy one palette into both slots: the native
        // renderers pick light or dark from the device, so each slot keeps
        // its own configured tokens.
        expect(config('native-ui.theme.light'))->toBe($config['theme']['light'])
            ->and(config('native-ui.theme.dark'))->toBe($config['theme']['dark'])
            ->and(config('native-ui.theme.light.background'))->toBe('#F8FAFC')
            ->and(config('native-ui.theme.dark.background'))->toBe('#0F172A');
    }
});

it('offers no in-app appearance override', function () {
    foreach (['/', '/gallery/inbox', '/recipes', '/components', '/components/button', '/components/progress', '/components/text-field', '/captures/button'] as $path) {
        $tree = Native::visit($path)->tree();

        expect(showcaseAppearanceNodes($tree, 'floating_overlay'))->toBe([])
            ->and(showcaseAppearanceNodes($tree, 'toggle'))->toBe([])
            ->and($tree['props'])->not->toHaveKey('dark');
    }
});
