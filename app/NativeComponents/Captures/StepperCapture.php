<?php

namespace App\NativeComponents\Captures;

use Illuminate\View\View;
use Native\Mobile\Edge\NativeComponent;

class StepperCapture extends NativeComponent
{
    public int $quantity = 5;

    public function navTitle(): string
    {
        return 'Firstlight Stepper';
    }

    public function render(): View
    {
        return view('native.captures.stepper');
    }
}
