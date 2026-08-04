<?php

namespace App\NativeComponents\Captures;

use Illuminate\View\View;
use Native\Mobile\Edge\NativeComponent;

class SearchFieldCapture extends NativeComponent
{
    public string $query = 'Referral';

    public function navTitle(): string
    {
        return 'Firstlight Search Field';
    }

    public function render(): View
    {
        return view('native.captures.search-field');
    }
}
