<?php

use Carbon\CarbonImmutable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Native\Mobile\Testing\Native;
use Native\Mobile\Testing\TestableComponent;

uses(RefreshDatabase::class);

beforeEach(function () {
    Cache::flush();
    CarbonImmutable::setTestNow('2026-11-13 14:05:00');
});

afterEach(fn () => CarbonImmutable::setTestNow());

function serverStatusCallout(TestableComponent $screen): ?array
{
    $callouts = showcaseNodesOfType($screen->tree(), 'firstlight.callout');

    return $callouts === [] ? null : [$callouts[0]['props']['tone'], $callouts[0]['props']['message'], $callouts[0]['props']['action_label']];
}

it('fetches on first open and says nothing while the data is fresh', function () {
    $screen = Native::visit('/servers')->assertSee('web-1')->assertAccessible();

    expect(serverStatusCallout($screen))->toBeNull();

    CarbonImmutable::setTestNow('2026-11-13 14:20:00');
    expect(serverStatusCallout(Native::visit('/servers')))->toBeNull();
});

it('shows the age of old data and the time of very old data', function () {
    $screen = Native::visit('/servers')->call('age', 15);
    expect(serverStatusCallout($screen))->toBe(['neutral', 'Updated 15 minutes ago', '']);

    $screen->call('age', 120);
    expect(serverStatusCallout($screen))->toBe(['neutral', 'Updated at 11:50 AM', '']);
});

it('keeps the cached list with a Try again notice when a refresh fails, and clears it when one lands', function () {
    $screen = Native::visit('/servers')->set('failRefreshes', true)->call('reload');
    expect(serverStatusCallout($screen))->toBe(['warning', "Couldn't refresh. Showing data from 2:05 PM.", 'Try again']);

    $screen->set('failRefreshes', false)->press("retryFreshness('servers')");
    expect(serverStatusCallout($screen))->toBeNull();
});

it('shows offline notices when the phone is offline', function () {
    Native::visit('/servers');

    Native::fakeBridge()->withOffline();
    CarbonImmutable::setTestNow('2026-11-13 14:30:00');
    $screen = Native::visit('/servers')->assertSee('web-1');
    expect(serverStatusCallout($screen))->toBe(['warning', 'Showing data from 2:05 PM', '']);

    $screen->call('forget');
    expect(serverStatusCallout($screen))->toBe(['info', "You're offline. This will load when you're back online.", '']);
});
