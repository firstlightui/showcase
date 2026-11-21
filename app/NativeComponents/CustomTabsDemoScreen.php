<?php

namespace App\NativeComponents;

use FirstlightUI\CustomTabs\Facades\CustomTabs;
use FirstlightUI\NativeComponent;
use Illuminate\View\View;

/** One sample tab of the Customise tabs recipe. */
class CustomTabsDemoScreen extends NativeComponent
{
    public string $tab = 'home';

    public function mount(string $tab = 'home'): void
    {
        $this->tab = $tab;
    }

    public function navTitle(): string
    {
        return CustomTabs::tabs('tabs-demo')[$this->tab]['label'] ?? 'Tabs';
    }

    public function editTabs(): void
    {
        $this->navigate(CustomTabs::editorUrl('tabs-demo'));
    }

    public function render(): View
    {
        return view('native.custom-tabs-demo');
    }
}
