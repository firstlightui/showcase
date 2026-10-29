<?php

namespace App\NativeComponents;

use Illuminate\View\View;

class BottomSheetShowcase extends ShowcaseScreen
{
    public bool $defaultVisible = false;

    public bool $programmaticVisible = false;

    public bool $billVisible = false;

    public int $dismissalCount = 0;

    public string $lastOutcome = 'No presentation yet';

    public function showDefault(): void
    {
        $this->defaultVisible = true;
    }

    public function showBill(): void
    {
        $this->billVisible = true;
    }

    public function closeBill(): void
    {
        $this->billVisible = false;
    }

    public function dismissDefault(): void
    {
        $this->defaultVisible = false;
        $this->dismissalCount++;
        $this->lastOutcome = 'Filters sheet dismissed';
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
        return 'Firstlight Bottom Sheet';
    }

    public function render(): View
    {
        return view('native.bottom-sheet-showcase');
    }
}
