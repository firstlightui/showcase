<?php

namespace App\NativeComponents;

use Illuminate\View\View;

class StepperShowcase extends ShowcaseScreen
{
    public int $minimumQuantity = 0;

    public int $maximumQuantity = 10;

    public int $errorQuantity = 4;

    public int $quantity = 5;

    public float $fractionalDose = 0.5;

    public int $approvedQuantity = 4;

    public int $rejectedAttempts = 0;

    public int $programmaticQuantity = 2;

    public int $quantityChanges = 0;

    public int $guestCount = 2;

    /** Counts accepted Quantity proposals; programmatic publications do not run this hook. */
    public function updatedQuantity(): void
    {
        $this->quantityChanges++;
    }

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
