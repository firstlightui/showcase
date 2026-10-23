<?php

namespace App\NativeComponents;

use FirstlightUI\Events\FeedbackActionPressed;
use FirstlightUI\Events\FeedbackDismissed;
use FirstlightUI\Facades\Feedback;
use FirstlightUI\Feedback\FeedbackDismissReason;
use Illuminate\View\View;
use Native\Mobile\Attributes\On;

final class TransientFeedbackShowcase extends ShowcaseScreen
{
    public bool $lastRemovalSucceeded = false;

    public bool $archived = false;

    public ?string $undoFeedbackId = null;

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

    public function archive(): void
    {
        if ($this->archived) {
            return;
        }

        $this->archived = true;
        $this->undoFeedbackId = Feedback::success('Item archived')
            ->action('Undo', 'undo-archive')
            ->send();
    }

    #[On(FeedbackActionPressed::class)]
    public function undoArchive(string $id, string $actionKey): void
    {
        if ($id === $this->undoFeedbackId && $actionKey === 'undo-archive') {
            $this->archived = false;
        }
    }

    #[On(FeedbackDismissed::class)]
    public function undoWindowEnded(string $id, FeedbackDismissReason $reason): void
    {
        if ($id === $this->undoFeedbackId) {
            $this->undoFeedbackId = null;
        }
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
