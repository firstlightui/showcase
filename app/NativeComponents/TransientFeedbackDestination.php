<?php

namespace App\NativeComponents;

use FirstlightUI\Facades\Feedback;
use Illuminate\View\View;

final class TransientFeedbackDestination extends ShowcaseScreen
{
    public bool $lastRemovalSucceeded = false;

    public function removeNavigationFeedback(): void
    {
        $this->lastRemovalSucceeded = Feedback::dismiss('navigation-feedback');
    }

    public function navTitle(): string
    {
        return 'Feedback Destination';
    }

    public function render(): View
    {
        return view('native.transient-feedback-destination', [
            'feedbackEventSummary' => $this->feedbackEventSummary(),
        ]);
    }
}
