<?php

namespace App\NativeComponents;

use Illuminate\View\View;

class SliderShowcase extends ShowcaseScreen
{
    public float $liveDose = 5.0;

    public float $blurTemperature = 0.25;

    public float $debouncedRatio = 0.5;

    public float $approvedDose = 4.0;

    public int $rejectedAttempts = 0;

    public float $programmaticDose = 2.0;

    public function rejectDose(float $value): void
    {
        $this->rejectedAttempts++;
    }

    public function publishHigherDose(): void
    {
        $this->programmaticDose = 8.0;
    }

    public function navTitle(): string
    {
        return 'Firstlight Slider';
    }

    public function render(): View
    {
        return view('native.slider-showcase');
    }
}
