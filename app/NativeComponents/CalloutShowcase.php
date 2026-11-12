<?php

namespace App\NativeComponents;

use Illuminate\View\View;

class CalloutShowcase extends ShowcaseScreen
{
    public int $actionCount = 0;

    public string $lastAction = 'No action yet';

    public string $syncStatus = 'syncing';

    public bool $syncNoticeShown = true;

    public function advanceSyncStatus(): void
    {
        $this->syncNoticeShown = true;
        $this->syncStatus = match ($this->syncStatus) {
            'syncing' => 'synced',
            'synced' => 'failed',
            default => 'syncing',
        };
    }

    public function removeSyncNotice(): void
    {
        $this->syncNoticeShown = false;
    }

    /** @return array{message: string, tone: string} */
    public function syncNotice(): array
    {
        return match ($this->syncStatus) {
            'synced' => ['message' => 'All appointments are up to date.', 'tone' => 'success'],
            'failed' => ['message' => 'Appointments could not be synced.', 'tone' => 'danger'],
            default => ['message' => 'Appointments are syncing in the background.', 'tone' => 'info'],
        };
    }

    public function reviewChanges(): void
    {
        $this->actionCount++;
        $this->lastAction = 'Review changes pressed';
    }

    public function navTitle(): string
    {
        return 'Firstlight Callout';
    }

    public function render(): View
    {
        return view('native.callout-showcase');
    }
}
