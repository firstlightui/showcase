<?php

namespace App\NativeComponents;

use App\Models\Post;
use FirstlightUI\NativeComponent;
use FirstlightUI\Pagination\ListPage;
use Illuminate\View\View;

class PostsScreen extends NativeComponent
{
    public string $query = '';

    public function mount(): void
    {
        $this->reload();
    }

    public function updatedQuery(): void
    {
        $this->reload();
    }

    public function reload(): void
    {
        $this->refreshList(fn (ListPage $page) => $this->posts($page));
    }

    public function loadMore(): void
    {
        $this->loadMoreList(fn (ListPage $page) => $this->posts($page));
    }

    public function openPost(int $id): void
    {
        $this->navigate('/posts/'.$id);
    }

    public function requestDeletion(int $id): void
    {
        $this->requestDestructiveListAction($id, 'delete');
    }

    public function cancelDeletion(): void
    {
        $this->cancelDestructiveListAction();
    }

    public function deletePost(): void
    {
        $this->confirmDestructiveListAction(
            destroy: fn (Post $post) => $post->delete(),
            ability: 'delete',
            successMessage: 'Post deleted',
        );
    }

    public function navTitle(): string
    {
        return 'Posts';
    }

    public function render(): View
    {
        return view('native.posts');
    }

    private function posts(ListPage $page)
    {
        return Post::query()
            ->when($this->query !== '', fn ($query) => $query->where('title', 'like', '%'.$this->query.'%'))
            ->latest()
            ->paginate(perPage: 20, page: $page->page);
    }
}
