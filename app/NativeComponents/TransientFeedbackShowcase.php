<?php

namespace App\NativeComponents;

use FirstlightUI\Facades\Feedback;
use Illuminate\View\View;

final class TransientFeedbackShowcase extends ShowcaseScreen
{
    public bool $lastRemovalSucceeded = false;

    public function publishDefault(): void
    {
        Feedback::message('Neutral information')->send();
    }

    public function publishSuccess(): void
    {
        Feedback::success('Appointment saved')->send();
    }

    public function publishWarning(): void
    {
        Feedback::warning('Check the appointment details')->send();
    }

    public function publishDanger(): void
    {
        Feedback::danger('Unable to save appointment')->send();
    }

    public function publishAction(): void
    {
        Feedback::success('Appointment saved')
            ->id('feedback-action')
            ->action('Undo', 'undo-save')
            ->send();
    }

    public function publishHeld(): void
    {
        $this->lastRemovalSucceeded = false;

        Feedback::message('Held until dismissed')
            ->id('held-feedback')
            ->hold()
            ->send();
    }

    public function removeHeld(): void
    {
        $this->lastRemovalSucceeded = Feedback::dismiss('held-feedback');
    }

    public function queueThree(): void
    {
        Feedback::message('First queued')->send();
        Feedback::success('Second queued')->send();
        Feedback::warning('Third queued')->send();
    }

    public function queueStableUpdate(): void
    {
        Feedback::message('Initial stable message')
            ->id('stable-update')
            ->send();

        Feedback::success('Still second')
            ->id('stable-update-follower')
            ->send();

        Feedback::warning('Updated in place')
            ->id('stable-update')
            ->hold()
            ->send();
    }

    public function navigateWithFeedback(): void
    {
        Feedback::success('Feedback survives navigation')
            ->id('navigation-feedback')
            ->hold()
            ->send();

        $this->navigate('/transient-feedback/destination');
    }

    public function navTitle(): string
    {
        return 'Firstlight Transient Feedback';
    }

    public function render(): View
    {
        return view('native.transient-feedback-showcase', [
            'feedbackEventSummary' => $this->feedbackEventSummary(),
        ]);
    }
}
