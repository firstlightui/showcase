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

it('floats one accessible appearance toggle over every interactive showcase page', function () {
    foreach (['/', '/button', '/progress', '/segmented', '/status-label', '/text-field'] as $path) {
        $screen = Native::visit($path);
        $overlays = showcaseAppearanceNodes($screen->tree(), 'floating_overlay');
        $toggles = showcaseAppearanceNodes($screen->tree(), 'toggle');

        expect($overlays)->toHaveCount(1)
            ->and($overlays[0]['props'])->toBe([
                'alignment' => 'bottom',
                'offset' => 12,
            ])->and($toggles)->toHaveCount(1)
            ->and($toggles[0]['props'])->toMatchArray([
                'label' => 'Dark mode',
                'value' => false,
                'a11y_label' => 'Preview dark appearance',
            ])->and($toggles[0]['props']['on_change'] ?? null)->toBeInt();

        $screen->assertAccessible();
    }
});

it('keeps deterministic capture routes free of interactive showcase chrome', function () {
    expect(showcaseAppearanceNodes(Native::visit('/captures/button')->tree(), 'floating_overlay'))
        ->toBe([]);
});

it('persists a dark preview across routes and applies the dark palette to both native schemes', function () {
    Native::visit('/')
        ->select('darkMode', true)
        ->assertSet('darkMode', true);

    expect(config('native-ui.theme.light.background'))->toBe('#0F172A')
        ->and(config('native-ui.theme.dark.background'))->toBe('#0F172A')
        ->and(config('native-ui.theme.light.on-background'))->toBe('#F8FAFC')
        ->and(config('native-ui.theme.dark.on-background'))->toBe('#F8FAFC');

    $detail = Native::visit('/button');
    $toggle = showcaseAppearanceNodes($detail->tree(), 'toggle')[0];

    expect($detail->get('darkMode'))->toBeTrue()
        ->and($toggle['props']['value'])->toBeTrue()
        ->and($toggle['props']['a11y_label'])->toBe('Preview light appearance');
});

it('restores the light palette when dark preview is switched off', function () {
    $screen = Native::visit('/')
        ->select('darkMode', true)
        ->select('darkMode', false)
        ->assertSet('darkMode', false);

    expect(config('native-ui.theme.light.background'))->toBe('#F8FAFC')
        ->and(config('native-ui.theme.dark.background'))->toBe('#F8FAFC')
        ->and(showcaseAppearanceNodes($screen->tree(), 'toggle')[0]['props']['a11y_label'])
        ->toBe('Preview dark appearance');
});
