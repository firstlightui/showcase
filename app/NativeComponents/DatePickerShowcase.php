<?php

namespace App\NativeComponents;

use Illuminate\View\View;

class DatePickerShowcase extends ShowcaseScreen
{
    public ?string $appointmentDate = null;

    public ?string $boundedDate = '2026-08-04';

    public ?string $approvedDate = '2026-08-12';

    public int $rejectedAttempts = 0;

    public ?string $programmaticDate = '2026-10-01';

    public function rejectDate(string $value): void
    {
        $this->rejectedAttempts++;
    }

    public function publishLaterDate(): void
    {
        $this->programmaticDate = '2026-11-15';
    }

    public function clearProgrammaticDate(): void
    {
        $this->programmaticDate = null;
    }

    public function navTitle(): string
    {
        return 'Firstlight Date Picker';
    }

    public function render(): View
    {
        return view('native.date-picker-showcase');
    }
}
