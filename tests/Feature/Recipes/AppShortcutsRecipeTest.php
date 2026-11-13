<?php

use App\Models\Post;
use Carbon\CarbonImmutable;
use FirstlightUI\AppShortcuts\Facades\AppShortcuts;
use FirstlightUI\Facades\Feedback;
use FirstlightUI\Testing\ScreenAssertions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Native\Mobile\Testing\Native;

uses(RefreshDatabase::class);

beforeEach(function () {
    ScreenAssertions::register();
    Feedback::fake();
});

it('lists the built-in shortcuts with where each appears, and opens their screens', function () {
    Native::visit('/shortcuts')
        ->assertAccessible()
        ->assertNavTitle('App shortcuts')
        ->assertSee('Siri: “New post in Firstlight Showcase” · Spotlight · App icon · Unlock first')
        ->assertSee('Spotlight · App icon');

    Native::visit('/shortcuts')->tap('New post')->assertNavigatedTo('/posts/create');
    Native::visit('/shortcuts')->tap('Open the vault')->assertNavigatedTo('/vault');
    Native::visit('/shortcuts')->tap('Try haptics')->assertNavigatedTo('/components/haptics');
});

it('runs the quick-post action from the native queue and opens the post it added', function () {
    $bridge = Native::fakeBridge()->respondTo('AppShortcuts.TakeQueued', ['invocations' => [
        ['id' => 'inv-1', 'shortcut' => 'quick-post', 'at' => CarbonImmutable::parse('2026-11-13 22:05:00 UTC')->getTimestampMs()],
    ]]);

    $screen = Native::visit('/shortcuts')
        ->assertSee('Siri: “Add a quick post in Firstlight Showcase” · Spotlight · App icon · Unlock first')
        ->assertSee('0 quick posts so far');

    expect(AppShortcuts::runQueuedActions())->toBe(1);
    $bridge->assertCalled('AppShortcuts.TakeQueued');

    $post = Post::query()->where('title', 'Quick post from 13 Nov, 22:05')->sole();
    expect($post->created_at->getTimestamp())->toBe(CarbonImmutable::parse('2026-11-13 22:05:00 UTC')->getTimestamp());

    $screen->assertFeedbackSent('A shortcut added a quick post')
        ->assertSee('1 quick post so far')
        ->tap('Open the newest quick post')
        ->assertNavigatedTo('/posts/'.$post->id);
});

it('says how to add a quick post before there is one, and runs the action when tapped', function () {
    Native::visit('/shortcuts')
        ->tap('Open the newest quick post')
        ->assertFeedbackSent('No quick posts yet. Run the shortcut from Siri, Spotlight or the app icon')
        ->tap('Add a quick post')
        ->assertFeedbackSent('Added a quick post')
        ->assertSee('1 quick post so far');
});

it('adds and removes a shortcut to the newest post at runtime', function () {
    $newest = Post::query()->latest()->orderByDesc('id')->firstOrFail();
    $bridge = Native::fakeBridge()->respondTo('AppShortcuts.Push', [])->respondTo('AppShortcuts.Remove', []);

    Native::visit('/shortcuts')
        ->tap('Add the newest post')
        ->assertFeedbackSent("Added \"{$newest->title}\". Long-press the app icon to see it")
        ->tap('Remove it')
        ->assertFeedbackSent('Removed the newest post shortcut');

    expect($bridge->callsTo('AppShortcuts.Push')[0]['params'])->toMatchArray([
        'id' => 'newest-post',
        'title' => $newest->title,
        'shortTitle' => 'Newest post',
        'path' => '/posts/'.$newest->id,
    ])->and($bridge->callsTo('AppShortcuts.Remove')[0]['params'])->toBe(['id' => 'newest-post']);
});

it('says when shortcuts need a native build', function () {
    Native::fakeBridge()->withoutCapability('AppShortcuts.Push');

    Native::visit('/shortcuts')
        ->tap('Add the newest post')
        ->assertFeedbackSent('Shortcuts need a native build of the app');
});
