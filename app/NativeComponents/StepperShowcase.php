<?php

namespace App\NativeComponents;

use Illuminate\View\View;

class StepperShowcase extends ShowcaseScreen
{
    public int $quantity = 5;

    public float $fractionalDose = 0.5;

    public int $approvedQuantity = 4;

    public int $rejectedAttempts = 0;

    public int $programmaticQuantity = 2;

    public function rejectQuantity(int $value): void
    {
        $this->rejectedAttempts++;
    }

    public function publishHigherQuantity(): void
    {
        $this->programmaticQuantity = 8;
    }

    public function navTitle(): string
    {
        return 'Firstlight Stepper';
    }

    public function render(): View
    {
        return view('native.stepper-showcase');
    }
}
