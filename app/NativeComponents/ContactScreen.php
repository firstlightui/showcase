<?php

namespace App\NativeComponents;

use App\Models\Contact;
use FirstlightUI\NativeComponent;
use Illuminate\View\View;

class ContactScreen extends NativeComponent
{
    public Contact $contact;

    public function navTitle(): string
    {
        return $this->contact->name;
    }

    public function render(): View
    {
        return view('native.contact');
    }
}
