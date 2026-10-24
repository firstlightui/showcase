<?php

namespace App\NativeComponents;

use App\Http\Requests\StorePostRequest;
use App\Models\Post;
use FirstlightUI\NativeComponent;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class CreatePostScreen extends NativeComponent
{
    public string $title = '';

    public string $body = '';

    public function save(): void
    {
        $saved = $this->submit(
            action: function (): void {
                $this->validate(StorePostRequest::class);

                if (Post::query()->where('title', $this->title)->exists()) {
                    throw ValidationException::withMessages([
                        'form' => 'A post with this title already exists.',
                    ]);
                }

                Post::query()->create([
                    'title' => $this->title,
                    'excerpt' => str($this->body)->limit(60)->toString(),
                    'body' => $this->body,
                ]);
            },
            successMessage: 'Post created',
            validate: false,
        );

        if ($saved) {
            $this->back();
        }
    }

    public function navTitle(): string
    {
        return 'New post';
    }

    public function render(): View
    {
        return view('native.create-post');
    }
}
