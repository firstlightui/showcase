<?php

use App\NativeComponents\LockScreen;
use FirstlightUI\AppGuard\AppLock;
use FirstlightUI\AppGuard\Events\Foregrounded;
use FirstlightUI\Testing\ScreenAssertions;
use Native\Mobile\Events\Biometric\Completed;
use Native\Mobile\Testing\Native;
use Native\Mobile\Testing\TestableComponent;

beforeEach(function () {
    ScreenAssertions::register();

    Native::fakeBridge()
        ->respondTo('AppGuard.SetPrivacyCover', [])
        ->respondTo('AppGuard.SetShieldTimeout', [])
        ->respondTo('AppGuard.ReleaseShield', [])
        ->respondTo('AppGuard.SetShieldImage', [])
        ->respondTo('Biometric.Prompt', []);
});

/** @return list<string> */
function appLockPromptIds(TestableComponent $screen): array
{
    return array_map(fn (array $call) => $call['params']['id'], $screen->bridge()->callsTo('Biometric.Prompt'));
}

it('sends a cold start to the lock screen and opens the protected screen once unlocked', function () {
    $vault = Native::visit('/vault')->assertReplacedWith('/lock');
    $vault->assertNativeCalled('AppGuard.SetPrivacyCover');

    $lock = $vault->follow()->assertScreen(LockScreen::class)->assertSet('prompting', true);

    $lock->emitNative(Completed::class, ['success' => true, 'id' => appLockPromptIds($lock)[0]])
        ->assertReplacedWith('/vault');

    expect(app(AppLock::class)->isLocked())->toBeFalse();

    Native::visit('/vault')->assertNoNavigation()->assertSet('balance', '$12,480.00');
});

it('locks again when the app returns behind the shield', function () {
    app(AppLock::class)->unlock();

    Native::visit('/vault')
        ->emitNative(Foregrounded::class, ['secondsAway' => 5, 'shielded' => false])
        ->assertNoNavigation()
        ->emitNative(Foregrounded::class, ['secondsAway' => 90, 'shielded' => true])
        ->assertReplacedWith('/lock');

    expect(app(AppLock::class)->isLocked())->toBeTrue();
});

it('releases the shield on a screen without protected content, then locks protected screens shown again', function () {
    app(AppLock::class)->unlock();
    $vault = Native::visit('/vault');

    $settings = Native::visit('/settings');
    Foregrounded::dispatch(90, true);

    $settings->assertNativeCalled('AppGuard.ReleaseShield');
    expect(app(AppLock::class)->isLocked())->toBeTrue();

    $vault->call('onResume')->assertReplacedWith('/lock');
});

it('leaves the shield to a protected screen', function () {
    app(AppLock::class)->unlock();

    Native::visit('/vault')
        ->emitNative(Foregrounded::class, ['secondsAway' => 90, 'shielded' => true])
        ->assertReplacedWith('/lock')
        ->assertNativeNotCalled('AppGuard.ReleaseShield');
});

it('suspends the shield while the system prompt is open and restores it after', function () {
    $lock = Native::test(LockScreen::class);

    $lock->emitNative(Completed::class, ['success' => false, 'id' => appLockPromptIds($lock)[0]]);

    $lock->assertNativeCallOrder(['AppGuard.ReleaseShield', 'AppGuard.SetShieldTimeout', 'Biometric.Prompt', 'AppGuard.SetShieldTimeout']);
    expect(array_column($lock->bridge()->callsTo('AppGuard.SetShieldTimeout'), 'params'))
        ->toBe([['seconds' => null], ['seconds' => 60]]);
});

it('stays locked after a failed or foreign result and asks again with a new prompt', function () {
    $lock = Native::test(LockScreen::class);
    $first = appLockPromptIds($lock)[0];

    $lock->emitNative(Completed::class, ['success' => true, 'id' => 'another-prompt'])
        ->assertNoNavigation()
        ->assertSet('prompting', true)
        ->emitNative(Completed::class, ['success' => false, 'id' => $first])
        ->assertNoNavigation()
        ->assertSet('failed', true)
        ->assertElement('firstlight.callout', fn (array $node) => $node['props']['message'] === "Couldn't confirm it's you. Try again."
            && $node['props']['tone'] === 'warning')
        ->tap('Unlock')
        ->assertSet('failed', false)
        ->assertSet('prompting', true);

    expect(appLockPromptIds($lock))->toHaveCount(2)
        ->and(appLockPromptIds($lock)[1])->not->toBe($first)
        ->and(app(AppLock::class)->isLocked())->toBeTrue();
});

it('shows the failure when the prompt cannot start', function () {
    Native::fakeBridge()
        ->respondTo('AppGuard.SetShieldTimeout', [])
        ->respondTo('AppGuard.ReleaseShield', [])
        ->respondTo('Biometric.Prompt', ['status' => 'error', 'code' => 'UNAVAILABLE', 'message' => 'No biometrics']);

    Native::test(LockScreen::class)
        ->assertSet('prompting', false)
        ->assertSet('failed', true)
        ->assertElement('firstlight.callout');
});

it('asks again when the app returns to an idle lock screen, but not mid-prompt', function () {
    // Opened by URI, as the app does: the app-lock middleware boots App Guard, whose listener releases the shield.
    $lock = Native::visit('/lock');

    $lock->emitNative(Foregrounded::class, ['secondsAway' => 1, 'shielded' => false]);
    expect(appLockPromptIds($lock))->toHaveCount(1);

    $lock->emitNative(Completed::class, ['success' => false, 'id' => appLockPromptIds($lock)[0]])
        ->emitNative(Foregrounded::class, ['secondsAway' => 30, 'shielded' => true]);

    $lock->assertNativeCalledTimes('AppGuard.ReleaseShield', 2);
    expect(appLockPromptIds($lock))->toHaveCount(2);
});

it('returns to recipes without unlocking when back is pressed', function () {
    Native::test(LockScreen::class)
        ->assertNavBarHidden()
        ->pressBack()
        ->assertReplacedWith('/recipes');

    expect(app(AppLock::class)->isLocked())->toBeTrue();
});

it('can leave the example when device authentication is unavailable', function () {
    Native::fakeBridge()
        ->respondTo('AppGuard.SetShieldTimeout', [])
        ->respondTo('AppGuard.ReleaseShield', [])
        ->respondTo('Biometric.Prompt', ['status' => 'error', 'code' => 'UNAVAILABLE']);

    Native::test(LockScreen::class)
        ->assertSet('failed', true)
        ->tap('Back to Recipes')
        ->assertReplacedWith('/recipes');

    expect(app(AppLock::class)->isLocked())->toBeTrue();
});

it('ignores a successful biometric callback after leaving the example', function () {
    $lock = Native::test(LockScreen::class);
    $prompt = appLockPromptIds($lock)[0];

    $lock->tap('Back to Recipes')
        ->assertReplacedWith('/recipes');

    $lock->instance()->biometricCompleted(success: true, id: $prompt);

    expect(app(AppLock::class)->isLocked())->toBeTrue();
});
