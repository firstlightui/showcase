<?php

namespace App\NativeComponents;

use Illuminate\View\View;

class ButtonShowcase extends ShowcaseScreen
{
    public int $pressCount = 0;

    public function recordPress(): void
    {
        $this->pressCount++;
    }

    public function navTitle(): string
    {
        return 'Firstlight Button';
    }

    public function render(): View
    {
        return view('native.button-showcase');
    }
}
