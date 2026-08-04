<?php

namespace App\NativeComponents;

use Illuminate\View\View;
use Native\Mobile\Edge\NativeComponent;

class SwitchShowcase extends NativeComponent
{
    public bool $notificationsOff = false;

    public bool $notificationsOn = true;

    public bool $disabledOff = false;

    public bool $disabledOn = true;

    public bool $helper = false;

    public bool $error = false;

    public bool $longLabel = false;

    public bool $rejected = false;

    public int $rejectedAttempts = 0;

    public bool $programmatic = false;

    public function rejectSwitch(bool $value): void
    {
        $this->rejectedAttempts++;
    }

    public function enableProgrammaticSwitch(): void
    {
        $this->programmatic = true;
    }

    public function resetProgrammaticSwitch(): void
    {
        $this->programmatic = false;
    }

    public function navTitle(): string
    {
        return 'Firstlight Switch';
    }

    public function render(): View
    {
        return view('native.switch-showcase');
    }
}
