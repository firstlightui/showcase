<?php

namespace App\NativeComponents\Captures;

use Illuminate\View\View;
use Native\Mobile\Edge\NativeComponent;

class SelectCapture extends NativeComponent
{
    public ?string $priority = null;

    /** @var array<string, string> */
    public array $priorityOptions = [
        'routine' => 'Routine',
        'urgent' => 'Urgent',
        'critical' => 'Critical',
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

    public function navTitle(): string
    {
        return 'Firstlight Select';
    }

    public function render(): View
    {
        return view('native.captures.select');
    }
}
