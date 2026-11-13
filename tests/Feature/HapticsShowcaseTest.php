<?php

use FirstlightUI\Facades\Haptics;
use FirstlightUI\Feedback\FeedbackStore;
use FirstlightUI\Haptics\Haptic;
use Illuminate\Support\Facades\Cache;
use Native\Mobile\Testing\Native;

/** @return list<array<string, mixed>> */
function hapticsShowcaseNodesOfType(array $tree, string $type): array
{
    $nodes = ($tree['type'] ?? null) === $type ? [$tree] : [];

    foreach ($tree['children'] ?? [] as $child) {
        $nodes = [...$nodes, ...hapticsShowcaseNodesOfType($child, $type)];
    }

    return $nodes;
}

beforeEach(fn () => app(FeedbackStore::class)->reset());
afterEach(fn () => app(FeedbackStore::class)->reset());

it('gives every haptic in the vocabulary its own natively played button', function () {
    $tree = Native::visit('/components/haptics')->assertAccessible()->tree();
    $native = array_values(array_filter(array_column(array_column(hapticsShowcaseNodesOfType($tree, 'firstlight.button'), 'props'), 'haptic')));
    $vocabulary = array_values(array_diff(array_column(Haptic::cases(), 'value'), ['none']));

    sort($native);
    sort($vocabulary);

    expect($tree['props']['nav_title'] ?? null)->toBe('Haptics')
        ->and($native)->toBe($vocabulary);
});

it('leaves native buttons to the device and plays impacts and Feedback outcomes from PHP', function () {
    $haptics = Haptics::fake();
    $screen = Native::visit('/components/haptics');

    $screen->tap('Keypress')->assertSee('Last played: keypress (native, as the tap landed)');
    $haptics->assertNothingPlayed();

    $screen->tap('Heavy');
    $screen->tap('Send both')->assertSee('Last played: error only (strongest of two Feedback)');

    expect($haptics->played())->toBe([
        ['impact' => 'heavy', 'intensity' => 1.0],
        ['haptic' => 'error'],
    ]);
});

it('turns haptics off for the install and remembers the choice', function () {
    $haptics = Haptics::fake();

    Native::visit('/components/haptics')
        ->toggle('hapticsEnabled', false)
        ->assertSet('hapticsEnabled', false);

    expect($haptics->enabledSetting())->toBeFalse()
        ->and(Cache::get('showcase.haptics'))->toBeFalse();

    Native::visit('/components/haptics')->assertSet('hapticsEnabled', false);
});
