<?php

use App\Models\Post;
use FirstlightUI\Facades\Feedback;
use FirstlightUI\Testing\ScreenAssertions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Native\Mobile\Testing\Native;

uses(RefreshDatabase::class);

beforeEach(fn () => ScreenAssertions::register());

it('shows Form Request errors on each native field and saves nothing', function () {
    Feedback::fake();

    Native::visit('/posts/create')
        ->set('title', 'Hi')
        ->set('body', 'Short')
        ->call('save')
        ->assertFieldError('title', 'The title field must be at least 3 characters.')
        ->assertFieldError('body', 'Write at least a sentence.')
        ->assertNoFeedbackSent();

    expect(Post::count())->toBe(30);
});

it('shows a form-level error from the action without field errors', function () {
    Native::visit('/posts/create')
        ->set('title', 'Sample post 1')
        ->set('body', 'A body long enough to pass.')
        ->call('save')
        ->assertNoFieldError('title')
        ->assertElement('firstlight.callout', fn (array $node) => ($node['props']['message'] ?? null) === 'A post with this title already exists.'
            && ($node['props']['tone'] ?? null) === 'danger');

    expect(Post::count())->toBe(30);
});

it('creates the post, sends Feedback, and goes back', function () {
    Feedback::fake();

    Native::visit('/posts/create')
        ->set('title', 'A brand new post')
        ->set('body', 'A body long enough to pass.')
        ->call('save')
        ->assertHasNoErrors()
        ->assertMissingElement('firstlight.callout')
        ->assertFeedbackSent('Post created')
        ->assertWentBack();

    expect(Post::where('title', 'A brand new post')->value('excerpt'))
        ->toBe('A body long enough to pass.');
});
