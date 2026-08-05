<?php

namespace App\NativeComponents\Captures;

use Illuminate\View\View;
use Native\Mobile\Edge\NativeComponent;

class ConfirmationDialogCapture extends NativeComponent
{
    public bool $visible = true;

    public function confirm(): void
    {
        $this->visible = false;
    }

    public function dismiss(): void
    {
        $this->visible = false;
    }

    public function navTitle(): string
    {
        return 'Firstlight Confirmation Dialog';
    }

    public function render(): View
    {
        return view('native.captures.confirmation-dialog');
    }
}
