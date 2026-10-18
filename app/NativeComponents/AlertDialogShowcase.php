<?php

namespace App\NativeComponents;

use Illuminate\View\View;
use Native\Mobile\AsyncTask;

class AlertDialogShowcase extends ShowcaseScreen
{
    public bool $defaultVisible = false;

    public bool $customActionVisible = false;

    public bool $longCopyVisible = false;

    public bool $programmaticVisible = false;

    public int $programmaticGeneration = 0;

    public int $acknowledgementCount = 0;

    public string $lastOutcome = 'No acknowledgement yet';

    public function showDefault(): void
    {
        $this->defaultVisible = true;
    }

    public function acknowledgeDefault(): void
    {
        $this->defaultVisible = false;
        $this->recordAcknowledgement('Changes saved acknowledged');
    }

    public function showCustomAction(): void
    {
        $this->customActionVisible = true;
    }

    public function acknowledgeCustomAction(): void
    {
        $this->customActionVisible = false;
        $this->recordAcknowledgement('Got it acknowledged');
    }

    public function showLongCopy(): void
    {
        $this->longCopyVisible = true;
    }

    public function acknowledgeLongCopy(): void
    {
        $this->longCopyVisible = false;
        $this->recordAcknowledgement('Long copy acknowledged');
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
                $this->lastOutcome = 'Timed close unavailable; acknowledge normally';
            }
        });
    }

    public function closeProgrammatic(): void
    {
        $this->programmaticVisible = false;
        $this->lastOutcome = 'Programmatic closure published no user outcome';
    }

    public function acknowledgeProgrammatic(): void
    {
        $this->programmaticVisible = false;
        $this->recordAcknowledgement('Programmatic example acknowledged');
    }

    public function navTitle(): string
    {
        return 'Firstlight Alert Dialog';
    }

    public function render(): View
    {
        return view('native.alert-dialog-showcase');
    }

    private function recordAcknowledgement(string $outcome): void
    {
        $this->acknowledgementCount++;
        $this->lastOutcome = $outcome;
    }
}
