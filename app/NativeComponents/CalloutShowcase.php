<?php

namespace App\NativeComponents;

use Illuminate\View\View;

class CalloutShowcase extends ShowcaseScreen
{
    public int $actionCount = 0;

    public string $lastAction = 'No action yet';

    public function reviewChanges(): void
    {
        $this->actionCount++;
        $this->lastAction = 'Review changes pressed';
    }

    public function navTitle(): string
    {
        return 'Firstlight Callout';
    }

    public function render(): View
    {
        return view('native.callout-showcase');
    }
}
