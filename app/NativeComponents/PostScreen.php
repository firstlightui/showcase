<?php

namespace App\NativeComponents;

use App\Models\Post;
use FirstlightUI\NativeComponent;
use Illuminate\View\View;

class PostScreen extends NativeComponent
{
    public Post $post;

    public function navTitle(): string
    {
        return $this->post->title;
    }

    public function render(): View
    {
        return view('native.post');
    }
}
