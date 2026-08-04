<?php

namespace App\NativeComponents;

use Illuminate\View\View;
use Native\Mobile\Edge\NativeComponent;

class StatusLabelShowcase extends NativeComponent
{
    public function navTitle(): string
    {
        return 'Firstlight Status Label';
    }

    public function render(): View
    {
        return view('native.status-label-showcase');
    }
}
