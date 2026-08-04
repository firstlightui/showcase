<?php

namespace App\NativeComponents\Captures;

use Illuminate\View\View;
use Native\Mobile\Edge\NativeComponent;

class ButtonCapture extends NativeComponent
{
    public function navTitle(): string
    {
        return 'Firstlight Button';
    }

    public function render(): View
    {
        return view('native.captures.button');
    }
}
