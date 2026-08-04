<?php

namespace App\NativeComponents\Captures;

use Illuminate\View\View;
use Native\Mobile\Edge\NativeComponent;

class ProgressCapture extends NativeComponent
{
    public function navTitle(): string
    {
        return 'Firstlight Progress';
    }

    public function render(): View
    {
        return view('native.captures.progress');
    }
}
