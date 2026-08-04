<?php

namespace App\NativeComponents\Captures;

use Illuminate\View\View;
use Native\Mobile\Edge\NativeComponent;

class IconButtonCapture extends NativeComponent
{
    public function capturePress(): void
    {
        // The stable capture fixture remains interactive for native semantics.
    }

    public function navTitle(): string
    {
        return 'Firstlight Icon Button';
    }

    public function render(): View
    {
        return view('native.captures.icon-button');
    }
}
