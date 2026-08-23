<?php

namespace App\NativeComponents\Captures;

use Illuminate\View\View;
use Native\Mobile\Edge\NativeComponent;

class BottomSheetCapture extends NativeComponent
{
    public bool $visible = true;

    public function dismiss(): void
    {
        $this->visible = false;
    }

    public function navTitle(): string
    {
        return 'Firstlight Bottom Sheet';
    }

    public function render(): View
    {
        return view('native.captures.bottom-sheet');
    }
}
