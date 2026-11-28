<?php

namespace App\NativeComponents\Captures;

use FirstlightUI\Media\MediaValue;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Native\Mobile\Edge\NativeComponent;

/** Stable Chat Composer documentation fixture: a draft with a photo and a PDF in the tray. */
class ChatComposerCapture extends NativeComponent
{
    public function navTitle(): string
    {
        return 'Firstlight Chat Composer';
    }

    public function render(): View
    {
        $disk = Storage::disk('mobile_public');
        if (! $disk->exists('chat-capture/profile.jpg')) {
            $disk->put('chat-capture/profile.jpg', (string) file_get_contents(resource_path('fixtures/media/profile.jpg')));
        }
        if (! $disk->exists('chat-capture/Booking.pdf')) {
            $disk->put('chat-capture/Booking.pdf', "%PDF-1.4\n%%EOF\n");
        }

        return view('native.captures.chat-composer', [
            'pending' => [
                new MediaValue('mobile_public', 'chat-capture/profile.jpg', 'image/jpeg', $disk->size('chat-capture/profile.jpg')),
                new MediaValue('mobile_public', 'chat-capture/Booking.pdf', 'application/pdf', $disk->size('chat-capture/Booking.pdf')),
            ],
        ]);
    }

    public function noop(): void {}

    public function remove(int $index): void {}
}
