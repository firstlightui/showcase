<?php

namespace App\Support;

use FirstlightUI\Events\FeedbackActionPressed;
use FirstlightUI\Events\FeedbackDismissed;

final class ShowcaseFeedbackLog
{
    private string $latest = 'No feedback events yet';

    public function recordAction(FeedbackActionPressed $event): void
    {
        $this->latest = "Action pressed · id={$event->id} · actionKey={$event->actionKey}";
    }

    public function recordDismissal(FeedbackDismissed $event): void
    {
        $this->latest = "Dismissed · id={$event->id} · reason={$event->reason->value}";
    }

    public function latest(): string
    {
        return $this->latest;
    }

    public function reset(): void
    {
        $this->latest = 'No feedback events yet';
    }
}
