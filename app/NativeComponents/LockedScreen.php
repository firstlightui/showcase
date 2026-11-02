<?php

namespace App\NativeComponents;

use App\Security\AppLock;
use FirstlightUI\AppGuard\Events\Foregrounded;
use FirstlightUI\NativeComponent;
use Native\Mobile\Attributes\On;

/**
 * Base for every screen that shows protected content.
 */
abstract class LockedScreen extends NativeComponent
{
    final public function mount(): void
    {
        $lock = app(AppLock::class);
        $lock->boot();

        if ($lock->isLocked()) {
            $this->showLockScreen();

            return;
        }

        $this->opened();
    }

    /**
     * Runs when the screen opens unlocked. Screens use this instead of mount().
     */
    protected function opened(): void {}

    /**
     * The app may have locked while another screen was on top.
     */
    public function onResume(): void
    {
        if (app(AppLock::class)->isLocked()) {
            $this->showLockScreen();
        }
    }

    #[On(Foregrounded::class)]
    public function appForegrounded(int $secondsAway, bool $shielded): void
    {
        if (! $shielded) {
            return;
        }

        app(AppLock::class)->lock();
        $this->showLockScreen();
    }

    private function showLockScreen(): void
    {
        $this->replace('/lock', ['return' => $this->nativeRouter?->currentUri()]);
    }
}
