<?php

namespace App\NativeComponents\Captures;

use Illuminate\View\View;
use Native\Mobile\Edge\NativeComponent;

class DatePickerCapture extends NativeComponent
{
    public ?string $appointmentDate = null;

    public ?string $reviewDate = '2026-08-04';

    public ?string $dischargeDate = null;

    public function navTitle(): string
    {
        return 'Firstlight Date Picker';
    }

    public function render(): View
    {
        return view('native.captures.date-picker');
    }
}
