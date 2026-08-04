<?php

namespace App\NativeComponents;

use Illuminate\View\View;

class TimePickerShowcase extends ShowcaseScreen
{
    public ?string $appointmentTime = null;

    public ?string $reviewTime = '14:30';

    public ?string $approvedTime = '09:15';

    public int $rejectedAttempts = 0;

    public ?string $programmaticTime = '16:00';

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
