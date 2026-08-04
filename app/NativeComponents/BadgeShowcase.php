<?php

namespace App\NativeComponents;

use Illuminate\View\View;

class BadgeShowcase extends ShowcaseScreen
{
    public function navTitle(): string
    {
        return 'Firstlight Badge';
    }

    public function render(): View
    {
        return view('native.badge-showcase');
    }
}
