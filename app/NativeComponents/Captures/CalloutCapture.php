<?php

namespace App\NativeComponents\Captures;

use Illuminate\View\View;
use Native\Mobile\Edge\NativeComponent;

class CalloutCapture extends NativeComponent
{
    public function reviewChanges(): void
    {
    }

    public function navTitle(): string
    {
        return 'Firstlight Callout';
    }

    public function render(): View
    {
        return view('native.captures.callout');
    }
}
