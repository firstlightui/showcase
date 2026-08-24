<?php

namespace App\NativeComponents\Captures;

use Illuminate\View\View;
use Native\Mobile\Edge\NativeComponent;

class AlertDialogCapture extends NativeComponent
{
    public bool $visible = true;

    public function acknowledge(): void
    {
        $this->visible = false;
    }

    public function navTitle(): string
    {
        return 'Firstlight Alert Dialog';
    }

    public function render(): View
    {
        return view('native.captures.alert-dialog');
    }
}
