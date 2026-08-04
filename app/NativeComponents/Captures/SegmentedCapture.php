<?php

namespace App\NativeComponents\Captures;

use Illuminate\View\View;
use Native\Mobile\Edge\NativeComponent;

class SegmentedCapture extends NativeComponent
{
    public string $queue = 'mine';

    public int $priority = 10;

    public string $errorQueue = 'all';

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

    public function navTitle(): string
    {
        return 'Firstlight Segmented';
    }

    public function render(): View
    {
        return view('native.captures.segmented');
    }
}
