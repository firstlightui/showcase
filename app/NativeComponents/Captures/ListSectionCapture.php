<?php

namespace App\NativeComponents\Captures;

use Illuminate\View\View;
use Native\Mobile\Edge\NativeComponent;

class ListSectionCapture extends NativeComponent
{
    public function capturePress(): void
    {
        // The stable capture fixture remains interactive for native semantics.
    }

    public function navTitle(): string
    {
        return 'Firstlight List Section';
    }

    public function render(): View
    {
        return view('native.captures.list-section');
    }
}
