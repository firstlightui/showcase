<?php

namespace App\NativeComponents;

use Illuminate\View\View;
use Native\Mobile\Attributes\Poll;

class TimePickerShowcase extends ShowcaseScreen
{
    public ?string $errorTime = null;

    public ?string $berlinTime = '18:30';

    public ?string $appointmentTime = null;

    public ?string $reviewTime = '14:30';

    public ?string $approvedTime = '09:15';

    public int $rejectedAttempts = 0;

    public ?string $programmaticTime = '16:00';

    public ?string $newYorkTime = '18:30';

    public ?string $medicationTime = null;

    public ?string $openTime = '08:00';

    public bool $openDisabled = false;

    public ?string $pendingOpenChange = null;

    public ?int $pendingOpenChangeAt = null;

    public string $lastOpenChange = '(none)';

    public function scheduleOpenChange(string $change): void
    {
        $this->pendingOpenChange = $change;
        $this->pendingOpenChangeAt = time() + 3;
    }

    #[Poll(1000)]
    public function applyOpenChange(): void
    {
        if ($this->pendingOpenChange === null || time() < $this->pendingOpenChangeAt) {
            return;
        }

        match ($this->pendingOpenChange) {
            'value' => $this->openTime = '12:45',
            'disabled' => $this->openDisabled = true,
        };

        $this->lastOpenChange = $this->pendingOpenChange;
        $this->pendingOpenChange = null;
        $this->pendingOpenChangeAt = null;
    }

    public function resetOpenChange(): void
    {
        $this->openTime = '08:00';
        $this->openDisabled = false;
        $this->pendingOpenChange = null;
        $this->pendingOpenChangeAt = null;
        $this->lastOpenChange = '(none)';
    }

    public function rejectTime(string $value): void
    {
        $this->rejectedAttempts++;
    }

    public function publishLaterTime(): void
    {
        $this->programmaticTime = '17:45';
    }

    public function clearProgrammaticTime(): void
    {
        $this->programmaticTime = null;
    }

    public function navTitle(): string
    {
        return 'Firstlight Time Picker';
    }

    public function render(): View
    {
        return view('native.time-picker-showcase');
    }
}
