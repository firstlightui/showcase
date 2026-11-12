<?php

namespace App\NativeComponents;

use Illuminate\View\View;
use Native\Mobile\AsyncTask;

class ButtonShowcase extends ShowcaseScreen
{
    public int $pressCount = 0;

    public bool $sending = false;

    public bool $sendingLocked = false;

    public int $sentCount = 0;

    public string $sendOutcome = 'Not sent yet';

    public function recordPress(): void
    {
        $this->pressCount++;
    }

    public function sendReminder(): void
    {
        if ($this->sending || $this->sendingLocked) {
            return;
        }

        $this->sending = true;
        $this->sendOutcome = 'Sending…';

        AsyncTask::dispatch(static function (): void {
            sleep(2);
        })->finished(function (): void {
            if ($this->sending) {
                $this->sending = false;
                $this->sentCount++;
                $this->sendOutcome = 'Reminder sent';
            }
        })->failed(function (): void {
            $this->sending = false;
            $this->sendOutcome = 'Sending unavailable; nothing was sent';
        });
    }

    public function toggleSendingLock(): void
    {
        $this->sendingLocked = ! $this->sendingLocked;
    }

    public function navTitle(): string
    {
        return 'Firstlight Button';
    }

    public function render(): View
    {
        return view('native.button-showcase');
    }
}
