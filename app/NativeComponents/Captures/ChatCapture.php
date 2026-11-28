<?php

namespace App\NativeComponents\Captures;

use Illuminate\View\View;
use Native\Mobile\Edge\NativeComponent;

/** Stable Chat documentation fixture: fixed times, so every capture matches, and short enough for one phone screen. */
class ChatCapture extends NativeComponent
{
    public function navTitle(): string
    {
        return 'Firstlight Chat';
    }

    public function render(): View
    {
        $photo = resource_path('fixtures/media/park.jpg');
        [$width, $height] = getimagesize($photo) ?: [0, 0];

        return view('native.captures.chat', [
            'messages' => [
                ['id' => 'm2', 'author' => 'sam', 'author-name' => 'Sam', 'body' => 'This is the place', 'sent-at' => '2026-11-27T18:41:00Z', 'attachments' => [
                    ['id' => 'p1', 'kind' => 'image', 'source' => $photo, 'mime' => 'image/jpeg', 'width' => $width, 'height' => $height],
                ]],
                ['id' => 'm4', 'author' => 'alex', 'author-name' => 'Alex', 'body' => 'And the booking', 'sent-at' => '2026-11-28T08:02:00Z', 'attachments' => [
                    ['id' => 'f1', 'kind' => 'file', 'name' => 'Booking.pdf', 'mime' => 'application/pdf', 'size' => 48_213],
                ]],
                ['id' => 'm5', 'author' => 'me', 'body' => 'Running 5 minutes late', 'sent-at' => '2026-11-28T08:05:00Z', 'status' => 'failed'],
                ['id' => 'm6', 'author' => 'me', 'body' => 'On my way', 'sent-at' => '2026-11-28T08:06:00Z', 'status' => 'read', 'read-by' => ['Sam', 'Alex']],
            ],
        ]);
    }

    public function resend(string $id): void {}

    public function noop(): void {}
}
