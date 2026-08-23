<?php

namespace App\NativeComponents;

use Illuminate\View\View;

class ListShowcase extends ShowcaseScreen
{
    public int $pressCount = 0;

    public string $lastPressed = 'None';

    public int $refreshCount = 0;

    public function recordAccount(): void
    {
        $this->record('Account');
    }

    public function recordNotifications(): void
    {
        $this->record('Notifications');
    }

    public function recordPrivacy(): void
    {
        $this->record('Privacy');
    }

    public function reloadItems(): void
    {
        $this->refreshCount++;
    }

    public function navTitle(): string
    {
        return 'Firstlight List';
    }

    public function render(): View
    {
        return view('native.list-showcase');
    }

    private function record(string $headline): void
    {
        $this->pressCount++;
        $this->lastPressed = $headline;
    }
}
