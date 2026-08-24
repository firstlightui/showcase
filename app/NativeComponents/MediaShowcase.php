<?php

namespace App\NativeComponents;

use FirstlightUI\Media\MediaStorage;
use FirstlightUI\Media\MediaValue;
use Illuminate\View\View;

class MediaShowcase extends ShowcaseScreen
{
    public ?MediaValue $avatar = null;

    public ?MediaValue $optionalCrop = null;

    public ?MediaValue $document = null;

    public ?MediaValue $disabled = null;

    public function mount(): void
    {
        $this->disabled = new MediaValue(
            'mobile_public',
            'avatars/disabled.jpg',
            'image/jpeg',
            8192,
            160,
            160,
        );
    }

    public function avatarChosen(string $tempPath): void
    {
        $previous = $this->avatar;
        $this->avatar = MediaStorage::commit($tempPath, 'mobile_public', 'avatars');
        MediaStorage::delete($previous);
    }

    public function avatarCleared(): void
    {
        MediaStorage::delete($this->avatar);
        $this->avatar = null;
    }

    public function optionalCropChosen(string $tempPath): void
    {
        $previous = $this->optionalCrop;
        $this->optionalCrop = MediaStorage::commit($tempPath, 'mobile_public', 'photos');
        MediaStorage::delete($previous);
    }

    public function optionalCropCleared(): void
    {
        MediaStorage::delete($this->optionalCrop);
        $this->optionalCrop = null;
    }

    public function documentChosen(string $tempPath): void
    {
        $previous = $this->document;
        $this->document = MediaStorage::commit($tempPath, 'mobile_public', 'docs');
        MediaStorage::delete($previous);
    }

    public function documentCleared(): void
    {
        MediaStorage::delete($this->document);
        $this->document = null;
    }

    public function navTitle(): string
    {
        return 'Firstlight Media';
    }

    public function render(): View
    {
        return view('native.media-showcase');
    }
}
