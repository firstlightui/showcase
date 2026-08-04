<?php

namespace App\NativeComponents;

use Illuminate\View\View;

class CheckboxShowcase extends ShowcaseScreen
{
    public bool $unchecked = false;

    public bool $checkedRequired = true;

    public bool $disabledUnchecked = false;

    public bool $disabledChecked = true;

    public bool $helper = false;

    public bool $error = false;

    public bool $longLabel = false;

    public bool $rejected = false;

    public int $rejectedAttempts = 0;

    public bool $programmatic = false;

    public function rejectCheckbox(bool $value): void
    {
        $this->rejectedAttempts++;
    }

    public function acceptProgrammaticAgreement(): void
    {
        $this->programmatic = true;
    }

    public function resetProgrammaticAgreement(): void
    {
        $this->programmatic = false;
    }

    public function navTitle(): string
    {
        return 'Firstlight Checkbox';
    }

    public function render(): View
    {
        return view('native.checkbox-showcase');
    }
}
