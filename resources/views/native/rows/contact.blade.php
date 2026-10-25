@php($contact = $contactWindow[$index])

<firstlight:list-item
    :headline="$contact->name"
    :supporting="$contact->email"
    trailing-icon="chevron-right"
    trailing-icon-ios="chevron.right"
    trailing-icon-android="chevron_right"
    @press="openContact({{ $contact->id }})"
/>
