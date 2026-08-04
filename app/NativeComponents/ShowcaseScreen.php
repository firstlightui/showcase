<?php

namespace App\NativeComponents;

use App\Support\ShowcaseAppearance;
use Native\Mobile\Edge\NativeComponent;

abstract class ShowcaseScreen extends NativeComponent
{
    public bool $darkMode = false;

    public function mount(): void
    {
        $appearance = app(ShowcaseAppearance::class);

        $this->darkMode = $appearance->prefersDark();
        $appearance->apply($this->darkMode);
    }

    public function updatedDarkMode(bool $darkMode): void
    {
        app(ShowcaseAppearance::class)->remember($darkMode);
    }

    public function showsBackButton(): bool
    {
        return true;
    }
}
