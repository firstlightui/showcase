<?php

namespace App\NativeComponents;

use Illuminate\View\View;

class ActivityIndicatorShowcase extends ShowcaseScreen
{
    public bool $loading = true;

    public string $mountedSize = 'sm';

    public string $mountedLabel = 'Syncing appointments';

    public int $mountedUpdates = 0;

    public function showIndicator(): void
    {
        $this->loading = true;
    }

    public function hideIndicator(): void
    {
        $this->loading = false;
    }

    public function updateMountedIndicator(): void
    {
        $this->mountedSize = match ($this->mountedSize) {
            'sm' => 'md',
            'md' => 'lg',
            default => 'sm',
        };
        $this->mountedLabel = $this->mountedLabel === 'Syncing appointments'
            ? 'Syncing appointment reminders'
            : 'Syncing appointments';
        $this->mountedUpdates++;
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
