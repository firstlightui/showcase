<?php

namespace App\NativeComponents;

use Illuminate\View\View;

/**
 * Entry points to the Firstlight plugin recipes. Each opens the guide's own
 * screen and layout, so it runs exactly as the how-to shows it.
 */
class PluginsScreen extends ShowcaseScreen
{
    public function openVault(): void
    {
        $this->navigate('/vault');
    }

    public function openShortcuts(): void
    {
        $this->navigate('/shortcuts');
    }

    public function openCustomTabs(): void
    {
        $this->navigate('/tabs/home');
    }

    public function openScan(): void
    {
        $this->navigate('/scan');
    }

    public function openAsk(): void
    {
        $this->navigate('/ask');
    }

    public function showsBackButton(): bool
    {
        return false;
    }

    public function navTitle(): string
    {
        return 'Plugins';
    }

    public function render(): View
    {
        return view('native.plugins');
    }
}
