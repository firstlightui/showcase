<?php

namespace App\NativeComponents\Captures;

use Illuminate\View\View;
use Native\Mobile\Edge\NativeComponent;

class BadgeCapture extends NativeComponent
{
    public function navTitle(): string
    {
        return 'Firstlight Badge';
    }

    public function render(): View
    {
        return view('native.captures.badge');
    }
}
