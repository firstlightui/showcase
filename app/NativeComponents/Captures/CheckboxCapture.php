<?php

namespace App\NativeComponents\Captures;

use Illuminate\View\View;
use Native\Mobile\Edge\NativeComponent;

class CheckboxCapture extends NativeComponent
{
    public bool $acceptedTerms = false;

    public bool $diagnostics = true;

    public bool $disabledAgreement = true;

    public bool $agreementWithError = false;

    public function navTitle(): string
    {
        return 'Firstlight Checkbox';
    }

    public function render(): View
    {
        return view('native.captures.checkbox');
    }
}
