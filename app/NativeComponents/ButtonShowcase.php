<?php

namespace App\NativeComponents;

use Illuminate\View\View;
use Native\Mobile\Edge\NativeComponent;

class ButtonShowcase extends NativeComponent
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
