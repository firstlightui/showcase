<?php

namespace App\NativeComponents;

use Illuminate\View\View;

class ChoiceGroupShowcase extends ShowcaseScreen
{
    public ?string $priority = 'routine';

    /** @var list<string> */
    public array $notifications = ['email', 'push'];

    public ?string $rejectedPriority = 'routine';

    public int $rejectedAttempts = 0;

    public int $triageLevel = 10;

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
