<?php

namespace App\NativeComponents;

use Illuminate\View\View;

class ActivityIndicatorShowcase extends ShowcaseScreen
{
    public bool $loading = true;

    public function showIndicator(): void
    {
        $this->loading = true;
    }

    public function hideIndicator(): void
    {
        $this->loading = false;
    }

    public function navTitle(): string
    {
        return 'Firstlight Activity Indicator';
    }

    public function render(): View
    {
        return view('native.activity-indicator-showcase');
    }
}
