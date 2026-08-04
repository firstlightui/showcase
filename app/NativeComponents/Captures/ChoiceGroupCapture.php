<?php

namespace App\NativeComponents\Captures;

use Illuminate\View\View;
use Native\Mobile\Edge\NativeComponent;

class ChoiceGroupCapture extends NativeComponent
{
    public string $priority = 'urgent';

    /** @var list<string> */
    public array $notifications = ['email', 'push'];

    /** @var array<string, string> */
    public array $priorityOptions = [
        'routine' => 'Routine',
        'urgent' => 'Urgent',
        'critical' => 'Critical',
    ];

    /** @var list<array{value: string, label: string, disabled: bool}> */
    public array $notificationOptions = [
        ['value' => 'email', 'label' => 'Email', 'disabled' => false],
        ['value' => 'sms', 'label' => 'SMS', 'disabled' => true],
        ['value' => 'push', 'label' => 'Push notification', 'disabled' => false],
    ];

    public function navTitle(): string
    {
        return 'Firstlight Choice Group';
    }

    public function render(): View
    {
        return view('native.captures.choice-group');
    }
}
