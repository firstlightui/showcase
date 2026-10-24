<?php

use App\Models\Post;
use FirstlightUI\Facades\Feedback;
use FirstlightUI\Testing\ScreenAssertions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Native\Mobile\Testing\Native;

uses(RefreshDatabase::class);

beforeEach(fn () => ScreenAssertions::register());

it('loads the first page and appends the next on end reached', function () {
    $screen = Native::visit('/posts')
        ->assertListItemCount(20)
        ->assertListItem('Sample post 1')
        ->assertMissingListItem('Sample post 21');

    $screen->call('loadMore')
        ->assertListItemCount(30)
        ->assertListItem('Sample post 30');
});

it('restarts the list from page one for each search', function () {
    Native::visit('/posts')
        ->set('query', 'post 3')
        ->assertListItem('Sample post 3')
        ->assertListItem('Sample post 30')
        ->assertMissingListItem('Sample post 1')
        ->set('query', 'nothing matches')
        ->assertListItemCount(0)
        ->assertSee('No matching posts');
});

it('opens the detail screen for the pressed row', function () {
    Native::visit('/posts')
        ->pressListItem('Sample post 2')
        ->assertNavigatedTo('/posts/'.Post::where('title', 'Sample post 2')->value('id'));
});

it('deletes a row only after native confirmation', function () {
    Feedback::fake();

    $screen = Native::visit('/posts')
        ->pressListItem('Sample post 1', 'Delete')
        ->assertConfirmationDialog('Delete post?');

    expect(Post::where('title', 'Sample post 1')->exists())->toBeTrue();

    $screen->confirmDialog()
        ->assertNoConfirmationDialog()
        ->assertMissingListItem('Sample post 1')
        ->assertFeedbackSent('Post deleted');

    expect(Post::where('title', 'Sample post 1')->exists())->toBeFalse();
});

it('keeps the row when deletion is cancelled', function () {
    Native::visit('/posts')
        ->pressListItem('Sample post 1', 'Delete')
        ->cancelDialog()
        ->assertListItem('Sample post 1');

    expect(Post::where('title', 'Sample post 1')->exists())->toBeTrue();
});
