<?php

namespace App\NativeComponents;

use Illuminate\View\View;
use Native\Mobile\AsyncTask;

class IconButtonShowcase extends ShowcaseScreen
{
    public int $pressCount = 0;

    public string $lastAction = 'None';

    public bool $syncing = false;

    public bool $syncLocked = false;

    public int $syncCount = 0;

    public string $syncOutcome = 'Not synced yet';

    public function recordPress(string $action = 'Record press'): void
    {
        $this->pressCount++;
        $this->lastAction = $action;
    }

    public function syncInbox(): void
    {
        if ($this->syncing || $this->syncLocked) {
            return;
        }

        $this->syncing = true;
        $this->syncOutcome = 'Syncing…';

        AsyncTask::dispatch(static function (): void {
            sleep(2);
        })->finished(function (): void {
            if ($this->syncing) {
                $this->syncing = false;
                $this->syncCount++;
                $this->syncOutcome = 'Inbox synced';
            }
        })->failed(function (): void {
            $this->syncing = false;
            $this->syncOutcome = 'Sync unavailable; nothing changed';
        });
    }

    public function toggleSyncLock(): void
    {
        $this->syncLocked = ! $this->syncLocked;
    }

    public function navTitle(): string
    {
        return 'Firstlight Icon Button';
    }

    public function render(): View
    {
        return view('native.icon-button-showcase');
    }
}
