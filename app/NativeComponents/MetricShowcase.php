<?php

namespace App\NativeComponents;

use Illuminate\View\View;

class MetricShowcase extends ShowcaseScreen
{
    public int $waiting = 4;

    public function addWaiting(): void
    {
        $this->waiting++;
    }

    public function clearWaiting(): void
    {
        $this->waiting = 0;
    }

    public function waitingTone(): string
    {
        return match (true) {
            $this->waiting === 0 => 'success',
            $this->waiting >= 6 => 'danger',
            default => 'warning',
        };
    }

    public function navTitle(): string
    {
        return 'Firstlight Metric';
    }

    public function render(): View
    {
        return view('native.metric-showcase');
    }
}
