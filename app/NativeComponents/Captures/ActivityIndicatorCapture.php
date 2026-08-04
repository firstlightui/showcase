<?php

namespace App\NativeComponents\Captures;

use Illuminate\View\View;
use Native\Mobile\Edge\NativeComponent;

class ActivityIndicatorCapture extends NativeComponent
{
    public function navTitle(): string
    {
        return 'Firstlight Activity Indicator';
    }

    public function render(): View
    {
        return view('native.captures.activity-indicator');
    }
}
