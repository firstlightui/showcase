<?php

namespace App\Security;

use App\NativeComponents\LockedScreen;
use FirstlightUI\AppGuard\Facades\AppGuard;
use Native\Mobile\Edge\NativeComponent;

/**
 * Whether the app is locked. The flag lives in memory, so every cold start begins locked.
 */
class AppLock
{
    /** Seconds in the background before a return needs the lock screen. */
    public const TIMEOUT = 60;

    private bool $booted = false;

    private bool $unlocked = false;

    /**
     * Native state does not survive process death, so apply it once per PHP process.
     */
    public function boot(): void
    {
        if ($this->booted) {
            return;
        }

        $this->booted = true;
        AppGuard::setPrivacyCover(true);
        $this->resumeShield();
    }

    /**
     * Runs for every return from the background. A shielded return locks the app. Protected screens
     * swap themselves for the lock screen; on any other screen the shield is released here.
     */
    public function foregrounded(bool $shielded): void
    {
        if (! $shielded) {
            return;
        }

        $this->lock();

        if (! NativeComponent::active() instanceof LockedScreen) {
            AppGuard::releaseShield();
        }
    }

    public function isLocked(): bool
    {
        return ! $this->unlocked;
    }

    public function lock(): void
    {
        $this->unlocked = false;
    }

    public function unlock(): void
    {
        $this->unlocked = true;
    }

    /**
     * Android's passcode screen stops the app, which would otherwise count as a trip to the background.
     */
    public function suspendShield(): void
    {
        AppGuard::shieldAfter(null);
    }

    public function resumeShield(): void
    {
        AppGuard::shieldAfter(self::TIMEOUT);
    }
}
