<?php

namespace App\NativeComponents\Captures;

use Illuminate\View\View;
use Native\Mobile\Edge\NativeComponent;

class StatusLabelCapture extends NativeComponent
{
    public function navTitle(): string
    {
        return 'Firstlight Status Label';
    }

    public function render(): View
    {
        return view('native.captures.status-label');
    }
}
