<?php

namespace App\NativeComponents;

use Illuminate\View\View;

class ProgressShowcase extends ShowcaseScreen
{
    public float $uploadProgress = 0.25;

    public function advanceProgress(): void
    {
        $this->uploadProgress = min(1.0, $this->uploadProgress + 0.25);
    }

    public function resetProgress(): void
    {
        $this->uploadProgress = 0.25;
    }

    public function navTitle(): string
    {
        return 'Firstlight Progress';
    }

    public function render(): View
    {
        return view('native.progress-showcase');
    }
}
