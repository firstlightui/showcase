<?php

namespace App\NativeComponents\Captures;

use FirstlightUI\Media\MediaValue;
use Illuminate\View\View;
use Native\Mobile\Edge\NativeComponent;

class MediaCapture extends NativeComponent
{
    public ?MediaValue $profilePhoto = null;

    public ?MediaValue $contract = null;

    public ?MediaValue $disabledPhoto = null;

    public ?MediaValue $photoWithError = null;

    public function mount(): void
    {
        $this->profilePhoto = new MediaValue(
            'mobile_public',
            'avatars/profile.jpg',
            'image/jpeg',
            18432,
            320,
            320,
        );

        $this->contract = new MediaValue(
            'mobile_public',
            'docs/contract.pdf',
            'application/pdf',
            65536,
        );

        $this->disabledPhoto = new MediaValue(
            'mobile_public',
            'avatars/locked.jpg',
            'image/jpeg',
            10240,
            200,
            200,
        );

        $this->photoWithError = null;
    }

    public function navTitle(): string
    {
        return 'Firstlight Media';
    }

    public function render(): View
    {
        return view('native.captures.media');
    }
}
