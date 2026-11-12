<?php

namespace App\NativeComponents;

use Illuminate\View\View;

class TextFieldShowcase extends ShowcaseScreen
{
    public string $errorEmail = 'invalid-address';

    public string $email = 'clinician@example.com';

    public string $search = 'Referral';

    public string $password = 'correct horse battery staple';

    public string $patientId = 'PT-2048';

    public string $recipient = '';

    public string $cardNumber = '';

    public string $postcode = 'K1A0B1';

    public int $scanRequests = 0;

    public string $reference = 'REF-7731';

    public bool $referenceDisabled = true;

    public string $note = '';

    public string $lastSubmittedNote = '';

    public int $noteSubmits = 0;

    public string $lazyReferral = '';

    public string $liveReferral = '';

    public string $approvedWard = 'Ward 4';

    public int $rejectedWardEdits = 0;

    public string $fullName = '';

    public string $username = '';

    public string $newPassword = '';

    public string $oneTimeCode = '';

    public string $phone = '';

    public string $website = '';

    public string $dose = '';

    public string $wardCode = '';

    public string $flushNote = '';

    public string $flushSnapshot = '(none)';

    public int $flushPresses = 0;

    public function toggleReferenceDisabled(): void
    {
        $this->referenceDisabled = ! $this->referenceDisabled;
    }

    public function submitNote(string $value = ''): void
    {
        $this->lastSubmittedNote = $value;
        $this->noteSubmits++;
    }

    public function rejectWard(string $value): void
    {
        $this->rejectedWardEdits++;
    }

    public function recordFlush(): void
    {
        $this->flushPresses++;
        $this->flushSnapshot = $this->flushNote === '' ? '(empty)' : $this->flushNote;
    }

    public function resetEmail(): void
    {
        $this->email = 'clinician@example.com';
    }

    public function scanRecipient(): void
    {
        $this->scanRequests++;
        $this->recipient = 'PT-2048';
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
