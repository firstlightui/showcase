<?php

namespace App\NativeComponents;

use FirstlightUI\Media\MediaStorage;
use FirstlightUI\Media\MediaValue;
use Illuminate\Support\Str;
use Illuminate\View\View;

class MediaShowcase extends ShowcaseScreen
{
    public ?MediaValue $avatar = null;

    public ?MediaValue $optionalCrop = null;

    public ?MediaValue $document = null;

    public ?MediaValue $uncropped = null;

    public ?MediaValue $requiredCrop = null;

    public string $lastOutcome = 'No validation yet';

    protected array $rules = [
        'avatar' => 'required|image|max:10240',
        'document' => 'nullable|file|mimes:pdf|max:10240',
    ];

    public function avatarChosen(string $tempPath): void
    {
        $this->replaceMedia('avatar', $tempPath);
    }

    public function avatarCleared(): void
    {
        MediaStorage::delete($this->avatar);
        $this->avatar = null;
    }

    public function optionalCropChosen(string $tempPath): void
    {
        $this->replaceMedia('optionalCrop', $tempPath);
    }

    public function optionalCropCleared(): void
    {
        MediaStorage::delete($this->optionalCrop);
        $this->optionalCrop = null;
    }

    public function documentChosen(string $tempPath): void
    {
        $this->replaceMedia('document', $tempPath);
    }

    public function documentCleared(): void
    {
        MediaStorage::delete($this->document);
        $this->document = null;
    }

    public function uncroppedChosen(string $tempPath): void
    {
        $this->replaceMedia('uncropped', $tempPath);
    }

    public function uncroppedCleared(): void
    {
        MediaStorage::delete($this->uncropped);
        $this->uncropped = null;
    }

    public function requiredCropChosen(string $tempPath): void
    {
        $this->replaceMedia('requiredCrop', $tempPath);
    }

    public function requiredCropCleared(): void
    {
        MediaStorage::delete($this->requiredCrop);
        $this->requiredCrop = null;
    }

    public function validateMedia(): void
    {
        $this->lastOutcome = 'Validation requested';
        $this->validate();
        $this->lastOutcome = 'Media validation passed';
    }

    public function clearDemoMedia(): void
    {
        foreach (['avatar', 'optionalCrop', 'document', 'uncropped', 'requiredCrop'] as $field) {
            MediaStorage::delete($this->{$field});
            $this->{$field} = null;
        }

        $this->resetValidation();
        $this->lastOutcome = 'Demo media cleared';
    }

    public function navTitle(): string
    {
        return 'Firstlight Media';
    }

    public function render(): View
    {
        return view('native.media-showcase');
    }

    private function replaceMedia(string $field, string $tempPath): void
    {
        $previous = $this->{$field};
        $extension = pathinfo($tempPath, PATHINFO_EXTENSION);
        // A repeated pick can reuse a source filename; never delete its replacement.
        $this->{$field} = MediaStorage::commit(
            $tempPath, 'mobile_public', 'showcase', filename: Str::uuid().($extension === '' ? '' : '.'.$extension),
        );
        MediaStorage::delete($previous);
        $this->resetValidation($field);
        $this->lastOutcome = 'Committed '.$field;
    }
}
