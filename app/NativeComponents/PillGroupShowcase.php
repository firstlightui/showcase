<?php

namespace App\NativeComponents;

use Illuminate\View\View;
class PillGroupShowcase extends ShowcaseScreen
{
    public ?string $queue = 'mine';

    /** @var list<string> */
    public array $queues = ['mine', 'urgent'];

    public ?string $rejectedQueue = 'mine';

    public int $rejectedAttempts = 0;

    public int $priority = 10;

    /** @var array<string, string> */
    public array $queueOptions = [
        'mine' => 'Mine',
        'all' => 'All',
        'urgent' => 'Urgent',
    ];

    /** @var list<array{value: int, label: string, disabled: bool}> */
    public array $priorityOptions = [
        ['value' => 10, 'label' => 'Routine', 'disabled' => false],
        ['value' => 20, 'label' => 'Urgent', 'disabled' => true],
        ['value' => 30, 'label' => 'Immediate', 'disabled' => false],
    ];

    /** @var array<string, string> */
    public array $longOptions = [
        'clinical-review' => 'Awaiting clinical review',
        'assignment' => 'Ready for assignment',
        'follow-up' => 'Needs follow-up',
    ];

    public function rejectQueue(?string $queue): void
    {
        $this->rejectedAttempts++;
    }

    public function resetSelections(): void
    {
        $this->queue = 'mine';
        $this->queues = ['mine', 'urgent'];
    }

    public function navTitle(): string
    {
        return 'Firstlight Pill Group';
    }

    public function render(): View
    {
        return view('native.pill-group-showcase');
    }
}
