<?php

namespace App\NativeComponents;

use FirstlightUI\AppGuard\Concerns\RequiresUnlock;
use FirstlightUI\NativeComponent;
use Illuminate\View\View;

class VaultScreen extends NativeComponent
{
    use RequiresUnlock;

    public string $balance = '';

    /**
     * The app-lock middleware has already let this screen through, so the app is unlocked here.
     */
    public function mount(): void
    {
        $this->balance = '$12,480.00';
    }

    public function navTitle(): string
    {
        return 'Vault';
    }

    public function render(): View
    {
        return view('native.vault');
    }
}
