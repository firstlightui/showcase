<?php

namespace App\NativeComponents\Captures;

use FirstlightUI\Facades\Feedback;
use Illuminate\View\View;
use Native\Mobile\Edge\NativeComponent;

final class TransientFeedbackCapture extends NativeComponent
{
    public function mount(): void
    {
        Feedback::success('Appointment saved')
            ->id('transient-feedback-capture')
            ->action('Undo', 'undo-save')
            ->hold()
            ->send();
    }

    public function navTitle(): string
    {
        return 'Firstlight Transient Feedback';
    }

    public function render(): View
    {
        return view('native.captures.transient-feedback');
    }
}
