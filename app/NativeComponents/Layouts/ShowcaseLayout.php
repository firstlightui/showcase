<?php

namespace App\NativeComponents\Layouts;

use App\NativeComponents\ShowcaseHome;
use App\NativeComponents\ShowcaseScreen;
use Native\Mobile\Edge\Layouts\Builders\NavBar;
use Native\Mobile\Edge\Layouts\NativeLayout;
use Native\Mobile\Edge\NativeComponent;
use Native\Mobile\UI\Builders\FloatingOverlay;
use Native\Mobile\UI\Concerns\HasFloatingOverlay;

class ShowcaseLayout extends NativeLayout
{
    use HasFloatingOverlay;

    public function usesNativeChrome(): bool
    {
        return true;
    }

    public function navBar(NativeComponent $screen): ?NavBar
    {
        $bar = NavBar::make()
            ->title($screen->navTitle())
            ->displayMode($screen instanceof ShowcaseHome ? 'large' : 'inline');

        if ($screen instanceof ShowcaseScreen) {
            $bar->back($screen->showsBackButton());
        }

        return $bar;
    }

    public function floatingOverlay(NativeComponent $screen): ?FloatingOverlay
    {
        if (! $screen instanceof ShowcaseScreen) {
            return null;
        }

        return FloatingOverlay::make(view('native.partials.appearance-toggle'))
            ->bottom()
            ->offset(12);
    }
}
