<?php

namespace App\NativeComponents;

use Illuminate\View\View;

class SelectShowcase extends ShowcaseScreen
{
    public ?string $priority = null;

    public int $triageLevel = 20;

    public ?string $largeQueue = 'queue-4';

    public ?string $forcedQueue = 'mine';

    public string $approvedQueue = 'all';

    public int $rejectedAttempts = 0;

    /** @var array<string, string> */
    public array $priorityOptions = [
        'routine' => 'Routine',
        'urgent' => 'Urgent',
        'critical' => 'Critical',
    ];

    /** @var list<array{value: int, label: string, disabled?: bool}> */
    public array $triageOptions = [
        ['value' => 10, 'label' => 'Low'],
        ['value' => 20, 'label' => 'Medium'],
        ['value' => 30, 'label' => 'High'],
        ['value' => 40, 'label' => 'Unavailable', 'disabled' => true],
    ];

    /** @var array<string, string> */
    public array $largeQueueOptions = [
        'queue-1' => 'Admissions',
        'queue-2' => 'Billing',
        'queue-3' => 'Clinical review',
        'queue-4' => 'Documents',
        'queue-5' => 'Escalations',
        'queue-6' => 'Follow-up',
        'queue-7' => 'General enquiries',
        'queue-8' => 'Home visits',
        'queue-9' => 'Imaging',
        'queue-10' => 'Laboratory',
        'queue-11' => 'Medication',
        'queue-12' => 'Nursing',
        'queue-13' => 'Outpatients',
    ];

    public function rejectQueue(string $value): void
    {
        $this->rejectedAttempts++;
    }

    public function navTitle(): string
    {
        return 'Firstlight Select';
    }

    public function render(): View
    {
        return view('native.select-showcase');
    }
}
