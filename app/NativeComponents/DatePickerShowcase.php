<?php

namespace App\NativeComponents;

use Illuminate\View\View;
use Native\Mobile\Attributes\Poll;

class DatePickerShowcase extends ShowcaseScreen
{
    public ?string $errorDate = null;

    public ?string $berlinDate = '2026-12-24';

    public ?string $appointmentDate = null;

    public ?string $boundedDate = '2026-08-04';

    public ?string $approvedDate = '2026-08-12';

    public int $rejectedAttempts = 0;

    public ?string $programmaticDate = '2026-10-01';

    public ?string $inheritedDate = '2026-09-03';

    public ?string $followUpDate = null;

    public ?string $openDate = '2026-09-10';

    public string $openMin = '2026-09-01';

    public string $openMax = '2026-09-30';

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
            // Both replacements stay inside either set of bounds.
            'value' => $this->openDate = '2026-09-20',
            'bounds' => [$this->openMin, $this->openMax] = ['2026-08-15', '2026-10-15'],
            'disabled' => $this->openDisabled = true,
        };

        $this->lastOpenChange = $this->pendingOpenChange;
        $this->pendingOpenChange = null;
        $this->pendingOpenChangeAt = null;
    }

    public function resetOpenChange(): void
    {
        $this->openDate = '2026-09-10';
        $this->openMin = '2026-09-01';
        $this->openMax = '2026-09-30';
        $this->openDisabled = false;
        $this->pendingOpenChange = null;
        $this->pendingOpenChangeAt = null;
        $this->lastOpenChange = '(none)';
    }

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
