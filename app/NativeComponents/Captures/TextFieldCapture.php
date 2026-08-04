<?php

namespace App\NativeComponents\Captures;

use Illuminate\View\View;
use Native\Mobile\Edge\NativeComponent;

class TextFieldCapture extends NativeComponent
{
    public string $email = 'clinician@example.com';

    public string $password = 'correct horse battery staple';

    public function navTitle(): string
    {
        return 'Firstlight Text Field';
    }

    public function render(): View
    {
        return view('native.captures.text-field');
    }
}
