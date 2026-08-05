<?php

namespace App\NativeComponents;

use Illuminate\View\View;

class ListItemShowcase extends ShowcaseScreen
{
    public int $pressCount = 0;

    public string $lastPressed = 'None';

    public function recordAccount(): void
    {
        $this->record('Account');
    }

    public function recordProfile(): void
    {
        $this->record('Profile');
    }

    public function recordTeam(): void
    {
        $this->record('Team');
    }

    public function recordBilling(): void
    {
        $this->record('Billing');
    }

    public function navTitle(): string
    {
        return 'Firstlight List Item';
    }

    public function render(): View
    {
        return view('native.list-item-showcase');
    }

    private function record(string $headline): void
    {
        $this->pressCount++;
        $this->lastPressed = $headline;
    }
}
