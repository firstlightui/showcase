<?php

namespace App\NativeComponents;

use Illuminate\View\View;

class TextAreaShowcase extends ShowcaseScreen
{
    public string $notes = "Patient reports improved sleep.\nNo new adverse effects.";

    public string $handover = '';

    public string $summary = 'Review medications and arrange follow-up.';

    public string $approved = 'Approved clinical wording.';

    public int $rejectedAttempts = 0;

    public string $programmatic = 'Initial programmatic note.';

    public function rejectNotes(string $value): void
    {
        $this->rejectedAttempts++;
    }

    public function replaceProgrammaticNotes(): void
    {
        $this->programmatic = "Programmatic update.\nSecond line retained.";
    }

    public function resetProgrammaticNotes(): void
    {
        $this->programmatic = 'Initial programmatic note.';
    }

    public function navTitle(): string
    {
        return 'Firstlight Text Area';
    }

    public function render(): View
    {
        return view('native.text-area-showcase');
    }
}
