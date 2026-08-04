<?php

namespace App\NativeComponents\Captures;

use Illuminate\View\View;
use Native\Mobile\Edge\NativeComponent;

class TextAreaCapture extends NativeComponent
{
    public string $notes = "Patient reports improved sleep.\nNo new adverse effects.";

    public function navTitle(): string
    {
        return 'Firstlight Text Area';
    }

    public function render(): View
    {
        return view('native.captures.text-area');
    }
}
