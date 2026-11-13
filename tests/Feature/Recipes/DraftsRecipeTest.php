<?php

use App\Models\Post;
use FirstlightUI\Facades\Drafts;
use FirstlightUI\Facades\Feedback;
use FirstlightUI\Testing\ScreenAssertions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Native\Mobile\Testing\Native;
use Native\Mobile\Testing\TestableComponent;

uses(RefreshDatabase::class);

beforeEach(function () {
    ScreenAssertions::register();
    Drafts::flush();
    Feedback::fake();
});

function leaveEditPost(TestableComponent $screen): void
{
    $screen->pressBack()->assertWentBack();
    $screen->instance()->unmount();
}

it('keeps an unsaved edit when the user goes back, and restores it on reopen', function () {
    leaveEditPost(Native::visit('/posts/1/edit')->set('body', 'An unsaved edit.'));

    Native::visit('/posts/1/edit')
        ->assertSet('body', 'An unsaved edit.')
        ->assertSet('title', 'Sample post 1')
        ->assertFeedbackSent('Restored your unsaved changes.');

    expect(Post::find(1)->body)->toBe('Body text for sample post 1. Recipe screens read this from SQLite.');
});

it('keeps nothing once the edit is saved', function () {
    $screen = Native::visit('/posts/1/edit')->set('body', 'A saved edit.')->call('save')
        ->assertFeedbackSent('Post saved')
        ->assertWentBack();
    $screen->instance()->unmount();

    expect(Drafts::has(\App\NativeComponents\EditPostScreen::class, 'post:1'))->toBeFalse();

    Native::visit('/posts/1/edit')->assertSet('body', 'A saved edit.');
});

it('offers a draft of a post changed on another device instead of restoring it', function () {
    leaveEditPost(Native::visit('/posts/1/edit')->set('body', 'An unsaved edit.'));

    $this->travel(5)->seconds();
    Native::visit('/recipes')->call('changePostElsewhere');

    Native::visit('/posts/1/edit')
        ->assertSet('body', 'Changed on another device at '.now()->format('H:i:s').'.')
        ->assertFeedbackSent("This changed since you last edited it. Your unsaved changes weren't restored.");
});
