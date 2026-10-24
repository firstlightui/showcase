<?php

namespace App\NativeComponents;

use FirstlightUI\NativeComponent;
use Illuminate\View\View;

/** Destination for the settings recipe's Privacy row. */
class PrivacyScreen extends NativeComponent
{
    public function navTitle(): string
    {
        return 'Privacy';
    }

    public function render(): View
    {
        return view('native.privacy');
    }
}
