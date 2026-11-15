@php($contact = $contactWindow[$index])

<firstlight:list-item
    :headline="$contact->name"
    :supporting="$contact->email"
    navigates
    @press="openContact({{ $contact->id }})"
/>
