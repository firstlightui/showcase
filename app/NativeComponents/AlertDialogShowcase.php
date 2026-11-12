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

    public bool $packageLabelVisible = false;

    public bool $copyVisible = false;

    public int $copyGeneration = 0;

    public int $copyRevision = 1;

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

    public function showPackageLabel(): void
    {
        $this->packageLabelVisible = true;
    }

    public function acknowledgePackageLabel(): void
    {
        $this->packageLabelVisible = false;
        $this->recordAcknowledgement('Package OK acknowledged');
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
                $this->lastOutcome = 'Timed copy update unavailable; acknowledge normally';
            }
        });
    }

    public function republishCopy(): void
    {
        $this->copyRevision++;
    }

    public function acknowledgeCopyUpdate(): void
    {
        $this->copyVisible = false;
        $this->recordAcknowledgement('Copy update acknowledged');
    }

    public function copyMessage(): string
    {
        return $this->copyRevision === 1
            ? 'Wait without tapping. PHP will republish this message in about 3 seconds while the alert stays open.'
            : "PHP republished this message (revision {$this->copyRevision}). The alert stayed open; acknowledge it once.";
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
