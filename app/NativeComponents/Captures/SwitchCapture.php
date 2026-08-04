<?php

namespace App\NativeComponents\Captures;

use Illuminate\View\View;
use Native\Mobile\Edge\NativeComponent;

class SwitchCapture extends NativeComponent
{
    public bool $notifications = false;

    public bool $automaticUpdates = true;

    public bool $settingWithError = false;

    public function navTitle(): string
    {
        return 'Firstlight Switch';
    }

    public function render(): View
    {
        return view('native.captures.switch');
    }
}
