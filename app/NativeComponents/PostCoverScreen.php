<?php

namespace App\NativeComponents;

use App\Models\Post;
use FirstlightUI\Media\MediaStorage;
use FirstlightUI\Media\MediaValue;
use FirstlightUI\NativeComponent;
use Illuminate\View\View;

class PostCoverScreen extends NativeComponent
{
    public Post $post;

    public ?MediaValue $cover = null;

    /** @var array<string, string> */
    protected array $rules = [
        'cover' => 'nullable|image|max:4096',
    ];

    public function mount(): void
    {
        $this->cover = $this->savedCover();
    }

    public function coverChosen(string $tempPath): void
    {
        $this->discardDraft();
        $this->cover = MediaStorage::commit($tempPath, 'mobile_public', 'covers');
    }

    public function coverCleared(): void
    {
        $this->discardDraft();
        $this->cover = null;
    }

    public function save(): void
    {
        $saved = $this->submit(
            action: function (): void {
                $previous = $this->savedCover();

                $this->post->update([
                    'cover' => $this->cover === null ? null : get_object_vars($this->cover),
                ]);

                if ($previous !== null && $previous->path !== $this->cover?->path) {
                    MediaStorage::delete($previous);
                }
            },
            successMessage: 'Cover saved',
        );

        if ($saved) {
            $this->back();
        }
    }

    public function navTitle(): string
    {
        return 'Cover photo';
    }

    public function render(): View
    {
        return view('native.post-cover');
    }

    private function savedCover(): ?MediaValue
    {
        return $this->post->cover === null ? null : new MediaValue(...$this->post->cover);
    }

    /** Deletes the picked file only while it is an unsaved draft. */
    private function discardDraft(): void
    {
        if ($this->cover !== null && $this->cover->path !== $this->savedCover()?->path) {
            MediaStorage::delete($this->cover);
        }
    }
}
