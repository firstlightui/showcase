<?php

namespace App\NativeComponents;

use Illuminate\View\View;

class TextFieldShowcase extends ShowcaseScreen
{
    public string $email = 'clinician@example.com';

    public string $search = 'Referral';

    public string $password = 'correct horse battery staple';

    public string $patientId = 'PT-2048';

    public string $recipient = '';

    public int $scanRequests = 0;

    public function resetEmail(): void
    {
        $this->email = 'clinician@example.com';
    }

    public function scanRecipient(): void
    {
        $this->scanRequests++;
    }

    public function navTitle(): string
    {
        return 'Firstlight Text Field';
    }

    public function render(): View
    {
        return view('native.text-field-showcase');
    }
}
