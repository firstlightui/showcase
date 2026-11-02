<?php

namespace App\NativeComponents;

use Illuminate\View\View;

class VaultScreen extends LockedScreen
{
    public string $balance = '';

    protected function opened(): void
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
