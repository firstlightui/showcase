<?php

namespace App\NativeComponents;

use Illuminate\View\View;

class ProgressShowcase extends ShowcaseScreen
{
    public float $uploadProgress = 0.25;

    public ?float $importProgress = null;

    public function advanceImport(): void
    {
        $this->importProgress = match (true) {
            $this->importProgress === null => 0.0,
            $this->importProgress >= 1.0 => null,
            default => min(1.0, $this->importProgress + 0.5),
        };
    }

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
