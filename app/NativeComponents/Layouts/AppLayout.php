<?php

namespace App\NativeComponents\Layouts;

use FirstlightUI\NativeComponent as FirstlightScreen;
use Native\Mobile\Edge\Layouts\Builders\NavBar;
use Native\Mobile\Edge\Layouts\NativeLayout;
use Native\Mobile\Edge\NativeComponent;

class AppLayout extends NativeLayout
{
    public function usesNativeChrome(): bool
    {
        return true;
    }

    public function navBar(NativeComponent $screen): ?NavBar
    {
        $isRoot = $screen instanceof FirstlightScreen && $screen->isRootScreen();

        return NavBar::make()
            ->title($screen->navTitle())
            ->displayMode($isRoot ? 'large' : 'inline')
            ->back(! $isRoot);
    }
}
