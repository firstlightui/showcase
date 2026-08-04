<?php

namespace App\NativeComponents\Captures;

use Illuminate\View\View;
use Native\Mobile\Edge\NativeComponent;

class PillGroupCapture extends NativeComponent
{
    public string $queue = 'mine';

    /** @var list<string> */
    public array $queues = ['mine', 'urgent'];

    /** @var array<string, string> */
    public array $queueOptions = [
        'mine' => 'Mine',
        'all' => 'All',
        'urgent' => 'Urgent',
    ];

    /** @var list<array{value: string, label: string, disabled: bool}> */
    public array $reviewOptions = [
        ['value' => 'clinical', 'label' => 'Clinical review', 'disabled' => false],
        ['value' => 'assignment', 'label' => 'Ready for assignment', 'disabled' => true],
        ['value' => 'follow-up', 'label' => 'Needs follow-up', 'disabled' => false],
    ];

    public function navTitle(): string
    {
        return 'Firstlight Pill Group';
    }

    public function render(): View
    {
        return view('native.captures.pill-group');
    }
}
