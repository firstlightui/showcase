<?php

namespace App\NativeComponents;

use Illuminate\View\View;

class ModalShowcase extends ShowcaseScreen
{
    public bool $defaultVisible = false;

    public bool $lockedVisible = false;

    public bool $programmaticVisible = false;

    public int $dismissalCount = 0;

    public string $lastOutcome = 'No presentation yet';

    public function showDefault(): void
    {
        $this->defaultVisible = true;
    }

    public function dismissDefault(): void
    {
        $this->defaultVisible = false;
        $this->dismissalCount++;
        $this->lastOutcome = 'Account overlay dismissed';
    }

    public function showLocked(): void
    {
        $this->lockedVisible = true;
    }

    public function acknowledgeLocked(): void
    {
        $this->lockedVisible = false;
        $this->lastOutcome = 'Required notice acknowledged';
    }

    public function dismissLocked(): void
    {
        $this->lockedVisible = false;
    }

    public function showProgrammatic(): void
    {
        $this->programmaticVisible = true;
    }

    public function closeProgrammatic(): void
    {
        $this->programmaticVisible = false;
        $this->lastOutcome = 'Programmatic closure published no user outcome';
    }

    public function dismissProgrammatic(): void
    {
        $this->programmaticVisible = false;
        $this->dismissalCount++;
        $this->lastOutcome = 'Programmatic example dismissed';
    }

    public function navTitle(): string
    {
        return 'Firstlight Modal';
    }

    public function render(): View
    {
        return view('native.modal-showcase');
    }
}
