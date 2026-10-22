<?php

namespace App\NativeComponents\Captures;

use Illuminate\View\View;
use Native\Mobile\Edge\NativeComponent;

class MetricCapture extends NativeComponent
{
    public function navTitle(): string
    {
        return 'Firstlight Metric';
    }

    public function render(): View
    {
        return view('native.captures.metric');
    }
}
