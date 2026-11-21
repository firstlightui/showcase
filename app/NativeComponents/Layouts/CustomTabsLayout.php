<?php

namespace App\NativeComponents\Layouts;

use FirstlightUI\CustomTabs\Facades\CustomTabs;
use Native\Mobile\Edge\Layouts\Builders\TabBar;
use Native\Mobile\Edge\NativeComponent;

/**
 * The Customise tabs recipe: AppLayout's bar over a tab bar people arrange
 * themselves, declared in AppServiceProvider as `tabs-demo`.
 */
class CustomTabsLayout extends AppLayout
{
    public function tabBar(NativeComponent $screen): ?TabBar
    {
        return CustomTabs::bar('tabs-demo')->presentation('firstlight:tabs');
    }
}
