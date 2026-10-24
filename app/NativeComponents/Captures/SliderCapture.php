<?php

namespace App\NativeComponents\Captures;

use Illuminate\View\View;
use Native\Mobile\Edge\NativeComponent;

class SliderCapture extends NativeComponent
{
    public float $dose = 5.0;

    public float $offset = 0.25;

    public float $reviewDose = 4.0;

    public function navTitle(): string
    {
        return 'Firstlight Slider';
    }

    public function render(): View
    {
        return view('native.captures.slider');
    }
}
