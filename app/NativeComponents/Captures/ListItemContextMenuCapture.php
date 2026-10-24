<?php

namespace App\NativeComponents\Captures;

use Illuminate\View\View;
use Native\Mobile\Edge\NativeComponent;

class ListItemContextMenuCapture extends NativeComponent
{
    public function capturePress(): void
    {
        // The stable capture fixture remains interactive so the menu can be opened.
    }

    public function navTitle(): string
    {
        return 'Firstlight Context Menu';
    }

    public function render(): View
    {
        return view('native.captures.list-item-context-menu');
    }
}
