<?php

namespace App\NativeComponents\Captures;

use Illuminate\View\View;
use Native\Mobile\Edge\NativeComponent;

class TimePickerCapture extends NativeComponent
{
    public ?string $appointmentTime = null;

    public function navTitle(): string
    {
        return 'Firstlight Time Picker';
    }

    public function render(): View
    {
        return view('native.captures.time-picker');
    }
}
