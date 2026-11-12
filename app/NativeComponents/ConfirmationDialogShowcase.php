<?php

namespace App\NativeComponents;

use Illuminate\View\View;
use Native\Mobile\AsyncTask;

class ConfirmationDialogShowcase extends ShowcaseScreen
{
    public bool $defaultVisible = false;

    public bool $destructiveVisible = false;

    public bool $longCopyVisible = false;

    public bool $programmaticVisible = false;

    public int $programmaticGeneration = 0;

    public bool $packageLabelsVisible = false;

    public bool $copyVisible = false;

    public int $copyGeneration = 0;

    public int $copyRevision = 1;

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
        $this->programmaticGeneration++;
        $this->programmaticVisible = true;
    }

    public function showTimedProgrammatic(): void
    {
        $this->showProgrammatic();
        $generation = $this->programmaticGeneration;

        AsyncTask::dispatch(static function (): void {
            sleep(3);
        })->finished(function () use ($generation): void {
            if ($this->programmaticVisible && $this->programmaticGeneration === $generation) {
                $this->closeProgrammatic();
            }
        })->failed(function () use ($generation): void {
            if ($this->programmaticVisible && $this->programmaticGeneration === $generation) {
                $this->lastOutcome = 'Timed close unavailable; confirm or cancel normally';
            }
        });
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

    public function showPackageLabels(): void
    {
        $this->packageLabelsVisible = true;
    }

    public function confirmPackageLabels(): void
    {
        $this->packageLabelsVisible = false;
        $this->recordConfirmation('Package Confirm chosen');
    }

    public function dismissPackageLabels(): void
    {
        $this->packageLabelsVisible = false;
        $this->recordDismissal('Package Cancel chosen');
    }

    public function showCopyUpdate(): void
    {
        $this->copyGeneration++;
        $this->copyVisible = true;
        $generation = $this->copyGeneration;

        AsyncTask::dispatch(static function (): void {
            sleep(3);
        })->finished(function () use ($generation): void {
            if ($this->copyVisible && $this->copyGeneration === $generation) {
                $this->republishCopy();
            }
        })->failed(function () use ($generation): void {
            if ($this->copyVisible && $this->copyGeneration === $generation) {
                $this->lastOutcome = 'Timed copy update unavailable; confirm or cancel normally';
            }
        });
    }

    public function republishCopy(): void
    {
        $this->copyRevision++;
    }

    public function confirmCopyUpdate(): void
    {
        $this->copyVisible = false;
        $this->recordConfirmation('Copy update confirmed');
    }

    public function dismissCopyUpdate(): void
    {
        $this->copyVisible = false;
        $this->recordDismissal('Copy update cancelled');
    }

    public function copyMessage(): string
    {
        return $this->copyRevision === 1
            ? 'Wait without choosing. PHP will republish this message in about 3 seconds while the dialog stays open.'
            : "PHP republished this message (revision {$this->copyRevision}). The dialog stayed open; choose once.";
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
