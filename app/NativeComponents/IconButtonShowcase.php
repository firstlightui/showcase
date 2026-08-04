<?php

namespace App\NativeComponents;

use Illuminate\View\View;

class IconButtonShowcase extends ShowcaseScreen
{
    public int $pressCount = 0;

    public function recordPress(): void
    {
        $this->pressCount++;
    }

    public function navTitle(): string
    {
        return 'Firstlight Icon Button';
    }

    public function render(): View
    {
        return view('native.icon-button-showcase');
    }
}
