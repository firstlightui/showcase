<?php

namespace App\NativeComponents;

use Illuminate\View\View;

class ToolbarShowcase extends ShowcaseScreen
{
    public string $lastAction = 'None';

    public int $actionCount = 0;

    public int $comments = 3;

    public bool $markupAvailable = true;

    public function record(string $action): void
    {
        $this->actionCount++;
        $this->lastAction = $action;
    }

    public function attachPhoto(): void
    {
        $this->record('Attach photo');
    }

    public function attachFile(): void
    {
        $this->record('Attach file');
    }

    public function scanDocument(): void
    {
        // Disabled in the menu, so no renderer can dispatch it.
        $this->record('Scan document');
    }

    public function openComments(): void
    {
        $this->record('Comments');
        $this->comments = 0;
    }

    public function addComment(): void
    {
        $this->comments++;
    }

    public function navTitle(): string
    {
        return 'Firstlight Toolbar';
    }

    public function render(): View
    {
        return view('native.toolbar-showcase');
    }
}
