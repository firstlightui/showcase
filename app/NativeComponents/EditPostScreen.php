<?php

namespace App\NativeComponents;

use App\Models\Post;
use FirstlightUI\Concerns\KeepsDrafts;
use FirstlightUI\Drafts\Draft;
use FirstlightUI\NativeComponent;
use Illuminate\View\View;

class EditPostScreen extends NativeComponent
{
    use KeepsDrafts;

    public Post $post;

    public string $title = '';

    public string $body = '';

    /** @var array<string, string> */
    protected array $rules = [
        'title' => 'required|min:3',
        'body' => 'required',
    ];

    public function mount(): void
    {
        $this->title = $this->post->title;
        $this->body = $this->post->body;

        $this->restoreDraft("post:{$this->post->id}", basedOn: $this->post->updated_at?->toISOString());
    }

    protected function draft(): ?Draft
    {
        if ($this->title === $this->post->title && $this->body === $this->post->body) {
            return null;
        }

        return new Draft(
            "post:{$this->post->id}",
            ['title' => $this->title, 'body' => $this->body],
            basedOn: $this->post->updated_at?->toISOString(),
        );
    }

    public function save(): void
    {
        $saved = $this->submit(
            action: fn () => $this->post->update([
                'title' => $this->title,
                'excerpt' => str($this->body)->limit(60)->toString(),
                'body' => $this->body,
            ]),
            successMessage: 'Post saved',
        );

        if ($saved) {
            $this->back();
        }
    }

    public function navTitle(): string
    {
        return 'Edit post';
    }

    public function render(): View
    {
        return view('native.edit-post');
    }
}
