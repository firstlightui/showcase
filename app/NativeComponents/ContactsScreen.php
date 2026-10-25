<?php

namespace App\NativeComponents;

use App\Models\Contact;
use FirstlightUI\NativeComponent;
use Illuminate\Support\Facades\View as ViewFactory;
use Illuminate\View\View;
use Native\Mobile\Edge\Concerns\HasVirtualListWindow;

class ContactsScreen extends NativeComponent
{
    use HasVirtualListWindow;

    public function openContact(int $id): void
    {
        $this->navigate('/contacts/'.$id);
    }

    public function navTitle(): string
    {
        return 'Contacts';
    }

    public function render(): View
    {
        // One query for the rows in the current window, keyed by their
        // absolute index so each row view can find its own contact.
        $from = $this->virtualWindowFrom;

        $window = Contact::query()
            ->orderBy('name')
            ->orderBy('id')
            ->skip($from)
            ->take($this->virtualWindowTo - $from + 1)
            ->get()
            ->values()
            ->keyBy(fn (Contact $contact, int $offset) => $from + $offset);

        ViewFactory::share('contactWindow', $window);

        return view('native.contacts', [
            'total' => Contact::query()->count(),
        ]);
    }
}
