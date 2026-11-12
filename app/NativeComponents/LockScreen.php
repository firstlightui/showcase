<?php

namespace App\NativeComponents;

use FirstlightUI\AppGuard\AppLock;
use FirstlightUI\AppGuard\Events\Foregrounded;
use FirstlightUI\AppGuard\Facades\AppGuard;
use FirstlightUI\NativeComponent;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Native\Mobile\Attributes\On;
use Native\Mobile\Edge\Layouts\Builders\NavBarOptions;
use Native\Mobile\Events\Biometric\Completed;
use Native\Mobile\Facades\Biometrics;

class LockScreen extends NativeComponent
{
    protected bool $hidesNavBar = true;

    public bool $prompting = false;

    public bool $failed = false;

    private ?string $promptId = null;

    public function mount(): void
    {
        // The shield fades out over 200 ms, covering this first render.
        AppGuard::releaseShield();

        $this->unlock();
    }

    public function unlock(): void
    {
        $this->failed = false;
        $this->promptId = (string) Str::uuid();
        app(AppLock::class)->suspendShield();

        $this->prompting = Biometrics::prompt()->id($this->promptId)->prompt();

        if (! $this->prompting) {
            $this->finishPrompt(success: false);
        }
    }

    #[On(Completed::class)]
    public function biometricCompleted(bool $success, ?string $id = null): void
    {
        if ($id === null || $id !== $this->promptId) {
            return;
        }

        $this->finishPrompt($success);
    }

    /**
     * App Guard releases the shield over this screen; asking again is this screen's job.
     */
    #[On(Foregrounded::class)]
    public function appForegrounded(int $secondsAway, bool $shielded): void
    {
        if (! $this->prompting) {
            $this->unlock();
        }
    }

    public function onBackPressed(): void
    {
        $this->cancel();
    }

    public function cancel(): void
    {
        $this->promptId = null;
        $this->prompting = false;
        app(AppLock::class)->resumeShield();
        AppGuard::releaseShield();
        $this->replace('/recipes');
    }

    public function navigationOptions(): ?NavBarOptions
    {
        return NavBarOptions::make()->back(false);
    }

    public function render(): View
    {
        return view('native.lock');
    }

    private function finishPrompt(bool $success): void
    {
        $this->promptId = null;
        $this->prompting = false;
        app(AppLock::class)->resumeShield();

        if (! $success) {
            $this->failed = true;

            return;
        }

        app(AppLock::class)->unlock();
        $this->replace(app(AppLock::class)->pullIntended('/'));
    }
}
