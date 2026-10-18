<?php

namespace App\NativeComponents;

use Illuminate\View\View;

class IconButtonShowcase extends ShowcaseScreen
{
    public int $pressCount = 0;

    public string $lastAction = 'None';

    public function recordPress(string $action = 'Record press'): void
    {
        $this->pressCount++;
        $this->lastAction = $action;
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
