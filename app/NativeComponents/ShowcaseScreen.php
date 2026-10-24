<?php

namespace App\NativeComponents;

use App\Support\ShowcaseFeedbackLog;
use FirstlightUI\NativeComponent;

abstract class ShowcaseScreen extends NativeComponent
{
    /**
     * Tab roots (Gallery, Recipes, Components) show no back button; every
     * screen pushed from them does.
     */
    public function showsBackButton(): bool
    {
        return true;
    }

    protected function feedbackEventSummary(): string
    {
        return app(ShowcaseFeedbackLog::class)->latest();
    }
}
