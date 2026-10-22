<?php

namespace App\NativeComponents;

use Illuminate\View\View;

class MetricShowcase extends ShowcaseScreen
{
    public function navTitle(): string
    {
        return 'Firstlight Metric';
    }

    public function render(): View
    {
        return view('native.metric-showcase');
    }
}
