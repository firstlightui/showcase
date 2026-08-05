<?php

namespace App\NativeComponents;

use Illuminate\View\View;

class ConfirmationDialogShowcase extends ShowcaseScreen
{
    public bool $defaultVisible = false;

    public bool $destructiveVisible = false;

    public bool $longCopyVisible = false;

    public bool $programmaticVisible = false;

    public int $confirmationCount = 0;

    public int $dismissalCount = 0;

    public string $lastOutcome = 'No decision yet';

    public function showDefault(): void
    {
        $this->defaultVisible = true;
    }

    public function confirmDefault(): void
    {
        $this->defaultVisible = false;
        $this->recordConfirmation('Default action confirmed');
    }

    public function dismissDefault(): void
    {
        $this->defaultVisible = false;
        $this->recordDismissal('Default action cancelled');
    }

    public function showDestructive(): void
    {
        $this->destructiveVisible = true;
    }

    public function confirmDestructive(): void
    {
        $this->destructiveVisible = false;
        $this->recordConfirmation('Destructive action confirmed');
    }

    public function dismissDestructive(): void
    {
        $this->destructiveVisible = false;
        $this->recordDismissal('Destructive action cancelled');
    }

    public function showLongCopy(): void
    {
        $this->longCopyVisible = true;
    }

    public function confirmLongCopy(): void
    {
        $this->longCopyVisible = false;
        $this->recordConfirmation('Long-copy action confirmed');
    }

    public function dismissLongCopy(): void
    {
        $this->longCopyVisible = false;
        $this->recordDismissal('Long-copy action cancelled');
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

    public function confirmProgrammatic(): void
    {
        $this->programmaticVisible = false;
        $this->recordConfirmation('Programmatic example confirmed');
    }

    public function dismissProgrammatic(): void
    {
        $this->programmaticVisible = false;
        $this->recordDismissal('Programmatic example cancelled');
    }

    public function navTitle(): string
    {
        return 'Firstlight Confirmation Dialog';
    }

    public function render(): View
    {
        return view('native.confirmation-dialog-showcase');
    }

    private function recordConfirmation(string $outcome): void
    {
        $this->confirmationCount++;
        $this->lastOutcome = $outcome;
    }

    private function recordDismissal(string $outcome): void
    {
        $this->dismissalCount++;
        $this->lastOutcome = $outcome;
    }
}
