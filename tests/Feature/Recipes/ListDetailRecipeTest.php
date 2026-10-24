<?php

use App\Models\Post;
use App\NativeComponents\Layouts\AppLayout;
use App\NativeComponents\PostScreen;
use App\NativeComponents\PostsScreen;
use FirstlightUI\Testing\ScreenAssertions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Native\Mobile\Edge\NativeRouter;
use Native\Mobile\Testing\Native;

uses(RefreshDatabase::class);

beforeEach(fn () => ScreenAssertions::register());

/** A router whose stack is $depth screens deep, as on device after pushes. */
function recipeRouterWithDepth(int $depth): NativeRouter
{
    $router = new NativeRouter;

    Closure::bind(function () use ($depth) {
        for ($i = 0; $i < $depth; $i++) {
            $this->stack[] = ['component' => null, 'uri' => "/screen-{$i}", 'params' => []];
        }
    }, $router, NativeRouter::class)();

    return $router;
}

it('renders the list as a native stack root without a back button', function () {
    $tree = Native::visit('/posts')->tree();

    expect($tree['type'])->toBe('native_root_stack')
        ->and($tree['props']['title'] ?? null)->toBe('Posts')
        ->and($tree['props']['display_mode'] ?? null)->toBe('large')
        ->and($tree['props']['back'] ?? null)->toBeFalse();
});

it('gives a pushed screen an inline title and a back button', function () {
    $screen = new PostScreen;
    $screen->post = Post::where('title', 'Sample post 2')->firstOrFail();
    $screen->setRouter(recipeRouterWithDepth(2));

    expect((new AppLayout)->navBar($screen)->toRootProps())
        ->toMatchArray(['title' => 'Sample post 2', 'displayMode' => 'inline', 'back' => true]);
});

it('pushes the detail screen for a row and returns to the list', function () {
    $post = Post::where('title', 'Sample post 2')->firstOrFail();

    Native::visit('/posts')
        ->pressListItem('Sample post 2')
        ->assertNavigatedTo('/posts/'.$post->id)
        ->follow()
        ->assertScreen(PostScreen::class)
        ->assertSee($post->body)
        ->goBack()
        ->assertScreen(PostsScreen::class)
        ->assertListItem('Sample post 2');
});
