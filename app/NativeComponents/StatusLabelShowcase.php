<?php

namespace App\NativeComponents;

use Illuminate\View\View;

class StatusLabelShowcase extends ShowcaseScreen
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
