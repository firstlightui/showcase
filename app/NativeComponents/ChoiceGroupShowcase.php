<?php

namespace App\NativeComponents;

use Illuminate\View\View;

class ChoiceGroupShowcase extends ShowcaseScreen
{
    public ?string $errorPriority = null;

    public ?string $nextStep = 'consult';

    public ?string $priority = 'routine';

    /** @var list<string> */
    public array $notifications = ['email', 'push'];

    public array $favourites = ['consult'];

    public ?string $rejectedPriority = 'routine';

    public int $rejectedAttempts = 0;

    public int $triageLevel = 10;

    /** @var list<string> */
    public array $lockedNotifications = ['email'];

    public int $rejectedMultipleAttempts = 0;

    public ?string $unlabelledPriority = 'routine';

    public ?string $absentPriority = 'deferred';

    public ?string $validatedPriority = 'routine';

    /** @var list<string> */
    public array $emptyOptions = [];

    /** @var array<string, string> */
    public array $priorityOptions = [
        'routine' => 'Routine',
        'urgent' => 'Urgent',
        'critical' => 'Critical',
    ];

    /** @var array<string, string> */
    public array $notificationOptions = [
        'email' => 'Email',
        'sms' => 'SMS',
        'push' => 'Push notification',
    ];

    /** @var list<array{value: int, label: string, disabled: bool}> */
    public array $triageOptions = [
        ['value' => 10, 'label' => 'Routine review', 'disabled' => false],
        ['value' => 20, 'label' => 'Urgent review', 'disabled' => true],
        ['value' => 30, 'label' => 'Immediate clinical escalation', 'disabled' => false],
    ];

    /** @var array<string, string> */
    public array $longOptions = [
        'standard' => 'Continue with the standard referral workflow',
        'consult' => 'Request a specialist consultation before proceeding',
        'escalate' => 'Escalate immediately to the on-call clinical team',
    ];

    public function rejectPriority(string $priority): void
    {
        $this->rejectedAttempts++;
    }

    /** @param list<string> $notifications */
    public function rejectNotifications(array $notifications): void
    {
        $this->rejectedMultipleAttempts++;
    }

    public function clearPriority(): void
    {
        $this->priority = null;
    }

    public function updatedValidatedPriority(): void
    {
        $this->validateOnly(
            'validatedPriority',
            ['validatedPriority' => 'in:routine,urgent'],
            ['validatedPriority.in' => 'Critical needs a senior clinician; choose Routine or Urgent.'],
        );
    }

    public function resetChoices(): void
    {
        $this->priority = 'routine';
        $this->notifications = ['email', 'push'];
    }

    public function navTitle(): string
    {
        return 'Firstlight Choice Group';
    }

    public function render(): View
    {
        return view('native.choice-group-showcase');
    }
}
