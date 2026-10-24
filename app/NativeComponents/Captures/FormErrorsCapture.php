<?php

namespace App\NativeComponents\Captures;

use FirstlightUI\NativeComponent;
use Illuminate\View\View;

class FormErrorsCapture extends NativeComponent
{
    public string $name = '';

    public string $email = 'not-an-email';

    public bool $accepted = false;

    public function save(): void
    {
    }

    public function navTitle(): string
    {
        return 'Firstlight Form Errors';
    }

    public function render(): View
    {
        if ($this->getErrorBag()->isEmpty()) {
            $this->addError('name', 'The name field is required.');
            $this->addError('email', 'The email must be a valid email address.');
            $this->addError('accepted', 'The terms must be accepted.');
        }

        return view('native.captures.form-errors');
    }
}
