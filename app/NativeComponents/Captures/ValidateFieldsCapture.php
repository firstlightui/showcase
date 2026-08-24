<?php

namespace App\NativeComponents\Captures;

use FirstlightUI\Concerns\ValidatesFields;
use Illuminate\View\View;
use Native\Mobile\Edge\NativeComponent;

class ValidateFieldsCapture extends NativeComponent
{
    use ValidatesFields;

    public string $name = '';

    public string $email = 'not-an-email';

    public bool $accepted = false;

    public function save(): void
    {
    }

    public function navTitle(): string
    {
        return 'Firstlight Validate Fields';
    }

    public function render(): View
    {
        if ($this->getErrorBag()->isEmpty()) {
            $this->addError('name', 'The name field is required.');
            $this->addError('email', 'The email must be a valid email address.');
            $this->addError('accepted', 'The terms must be accepted.');
        }

        return view('native.captures.validate-fields');
    }
}
