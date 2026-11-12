<?php

namespace App\NativeComponents;

use FirstlightUI\Media\MediaStorage;
use FirstlightUI\Media\MediaValue;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class MediaShowcase extends ShowcaseScreen
{
    /** A 48 × 48 solid PNG that PHP publishes without a native pick. */
    private const SAMPLE_PNG = 'iVBORw0KGgoAAAANSUhEUgAAADAAAAAwCAIAAADYYG7QAAAAOklEQVR42u3OQQ0AAAgEoGtkNOsaxxbOBxsBSPW8EiEhISEhISEhISEhISEhISEhISEhISEhISGhOwszfybEAoDoTgAAAABJRU5ErkJggg==';

    public ?MediaValue $avatar = null;

    public ?MediaValue $optionalCrop = null;

    public ?MediaValue $document = null;

    public ?MediaValue $uncropped = null;

    public ?MediaValue $requiredCrop = null;

    public ?MediaValue $sample = null;

    public ?MediaValue $archiveScan = null;

    public int $rejectedScans = 0;

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

    public function publishSample(): void
    {
        $bytes = base64_decode(self::SAMPLE_PNG);
        $path = 'showcase/sample-'.Str::uuid().'.png';
        Storage::disk('mobile_public')->put($path, $bytes);
        $previous = $this->sample;
        $this->sample = new MediaValue('mobile_public', $path, 'image/png', strlen($bytes), 48, 48);
        MediaStorage::delete($previous);
        $this->lastOutcome = 'Published sample from PHP';
    }

    public function clearSampleFromPhp(): void
    {
        MediaStorage::delete($this->sample);
        $this->sample = null;
        $this->lastOutcome = 'Cleared sample from PHP';
    }

    public function sampleChosen(string $tempPath): void
    {
        $this->replaceMedia('sample', $tempPath);
    }

    public function sampleCleared(): void
    {
        MediaStorage::delete($this->sample);
        $this->sample = null;
    }

    public function rejectScan(string $tempPath = ''): void
    {
        $this->rejectedScans++;
        $this->lastOutcome = 'Rejected a change to the approved copy';
    }

    public function archiveScanChosen(string $tempPath): void
    {
        $previous = $this->archiveScan;
        $extension = pathinfo($tempPath, PATHINFO_EXTENSION);
        $this->archiveScan = MediaStorage::commit(
            $tempPath, 'local', 'showcase-archive', filename: Str::uuid().($extension === '' ? '' : '.'.$extension),
        );
        MediaStorage::delete($previous);
        $this->lastOutcome = 'Committed archiveScan to the local disk';
    }

    public function archiveScanCleared(): void
    {
        MediaStorage::delete($this->archiveScan);
        $this->archiveScan = null;
    }

    public function validateMedia(): void
    {
        $this->lastOutcome = 'Validation requested';
        $this->validate();
        $this->lastOutcome = 'Media validation passed';
    }

    public function clearDemoMedia(): void
    {
        foreach (['avatar', 'optionalCrop', 'document', 'uncropped', 'requiredCrop', 'sample', 'archiveScan'] as $field) {
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
