<?php

namespace App\NativeComponents\Captures;

use FirstlightUI\Media\MediaValue;
use Illuminate\Support\Facades\Storage;
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
        $this->profilePhoto = $this->samplePhoto('profile.jpg');

        $this->contract = new MediaValue(
            'mobile_public',
            'docs/contract.pdf',
            'application/pdf',
            65536,
        );

        $this->disabledPhoto = $this->samplePhoto('locked.jpg');

        $this->photoWithError = null;
    }

    /**
     * Copies a bundled sample photo to mobile_public on first use, so the
     * field shows a real thumbnail in documentation captures.
     */
    private function samplePhoto(string $name): MediaValue
    {
        $source = resource_path('fixtures/media/'.$name);
        $path = 'avatars/'.$name;
        $disk = Storage::disk('mobile_public');

        if (! $disk->exists($path)) {
            $disk->put($path, (string) file_get_contents($source));
        }

        [$width, $height] = getimagesize($source) ?: [null, null];

        return new MediaValue('mobile_public', $path, 'image/jpeg', (int) filesize($source), $width, $height);
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
