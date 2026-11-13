<?php

namespace App\NativeComponents;

use App\Models\Post;
use FirstlightUI\Facades\Feedback;
use Illuminate\View\View;

/**
 * Entry points to the documented app recipes. Each opens the guide's own
 * screen and layout, so it runs exactly as the how-to shows it.
 */
class RecipesScreen extends ShowcaseScreen
{
    public function openPosts(): void
    {
        $this->navigate('/posts');
    }

    public function openPostDetail(): void
    {
        $id = Post::query()->latest()->orderByDesc('id')->value('id');

        $this->navigate($id === null ? '/posts' : '/posts/'.$id);
    }

    public function openContacts(): void
    {
        $this->navigate('/contacts');
    }

    public function openPostCover(): void
    {
        $id = Post::query()->latest()->orderByDesc('id')->value('id');

        $this->navigate($id === null ? '/posts' : '/posts/'.$id.'/cover');
    }

    public function openEditPost(): void
    {
        $id = Post::query()->latest()->orderByDesc('id')->value('id');

        $this->navigate($id === null ? '/posts' : '/posts/'.$id.'/edit');
    }

    /** Stands in for an edit made on another device, so a kept draft is now of an older version. */
    public function changePostElsewhere(): void
    {
        $post = Post::query()->latest()->orderByDesc('id')->first();

        if ($post === null) {
            return;
        }

        $post->update(['body' => 'Changed on another device at '.now()->format('H:i:s').'.']);

        Feedback::success("Changed \"{$post->title}\" as if on another device")->send();
    }

    public function openServerStatus(): void
    {
        $this->navigate('/servers');
    }

    public function openNewPost(): void
    {
        $this->navigate('/posts/create');
    }

    public function openSettings(): void
    {
        $this->navigate('/settings');
    }

    public function openVault(): void
    {
        $this->navigate('/vault');
    }

    public function showsBackButton(): bool
    {
        return false;
    }

    public function navTitle(): string
    {
        return 'Recipes';
    }

    public function render(): View
    {
        return view('native.recipes');
    }
}
