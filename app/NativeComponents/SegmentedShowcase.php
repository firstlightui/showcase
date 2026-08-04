<?php

namespace App\NativeComponents;

use Illuminate\View\View;

class SegmentedShowcase extends ShowcaseScreen
{
    public string $simple = 'Mine';

    public string $queue = 'mine';

    public string $rejectedQueue = 'mine';

    public int $rejectedQueueAttempts = 0;

    public int $priority = 10;

    public ?string $unselected = null;

    public string $disabledSelection = 'locked';

    public string $requiredSelection = 'mine';

    public string $errorSelection = 'mine';

    public string $longSelection = 'clinical-review';

    /** @var list<string> */
    public array $simpleOptions = ['Mine', 'All'];

    /** @var array<string, string> */
    public array $queueOptions = [
        'mine' => 'Mine',
        'all' => 'All',
    ];

    /** @var list<array{value: int, label: string, disabled: bool}> */
    public array $priorityOptions = [
        ['value' => 10, 'label' => 'Routine', 'disabled' => false],
        ['value' => 20, 'label' => 'Urgent', 'disabled' => true],
    ];

    /** @var array<string, string> */
    public array $disabledOptions = [
        'locked' => 'Locked',
        'archived' => 'Archived',
    ];

    /** @var array<string, string> */
    public array $longOptions = [
        'clinical-review' => 'Awaiting clinical review',
        'assignment' => 'Ready for assignment',
    ];

    /** @var list<string> */
    public array $emptyOptions = [];

    public function resetSelections(): void
    {
        $this->queue = 'mine';
        $this->priority = 10;
    }

    public function rejectQueue(string $queue): void
    {
        $this->rejectedQueueAttempts++;
    }

    public function navTitle(): string
    {
        return 'Firstlight Segmented';
    }

    public function render(): View
    {
        return view('native.segmented-showcase');
    }
}
