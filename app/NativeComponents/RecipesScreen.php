<?php

namespace App\NativeComponents;

use App\Models\Post;
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

    public function openNewPost(): void
    {
        $this->navigate('/posts/create');
    }

    public function openSettings(): void
    {
        $this->navigate('/settings');
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
