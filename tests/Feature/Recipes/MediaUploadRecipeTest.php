<?php

use App\Models\Post;
use FirstlightUI\Facades\Feedback;
use FirstlightUI\Testing\ScreenAssertions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Native\Mobile\Testing\Native;

uses(RefreshDatabase::class);

beforeEach(function () {
    ScreenAssertions::register();
    Storage::fake('mobile_public');
    $this->post = Post::where('title', 'Sample post 1')->firstOrFail();
});

it('saves a picked cover to the post and goes back', function () {
    Feedback::fake();
    $photo = UploadedFile::fake()->image('beach.jpg', 1600, 900);

    $screen = Native::visit("/posts/{$this->post->id}/cover")
        ->call('coverChosen', $photo->getPathname())
        ->call('save')
        ->assertHasNoErrors()
        ->assertFeedbackSent('Cover saved')
        ->assertWentBack();

    $cover = $this->post->fresh()->cover;
    expect($cover['path'])->toStartWith('covers/')
        ->and($cover['width'])->toBe(1600)
        ->and($cover['height'])->toBe(900);
    Storage::disk('mobile_public')->assertExists($cover['path']);
});

it('rejects a file that is not an image and saves nothing', function () {
    $notes = UploadedFile::fake()->createWithContent('notes.txt', 'Not an image');

    Native::visit("/posts/{$this->post->id}/cover")
        ->call('coverChosen', $notes->getPathname())
        ->call('save')
        ->assertHasErrors(['cover' => 'The cover field must be an image.'])
        ->assertElement('firstlight.media', fn (array $node) => $node['props']['error'] === 'The cover field must be an image.');

    expect($this->post->fresh()->cover)->toBeNull();
});

it('deletes a replaced draft but keeps the saved cover until Save', function () {
    $first = UploadedFile::fake()->image('first.jpg', 1600, 900);
    $draft = UploadedFile::fake()->image('draft.jpg', 1600, 900);
    $final = UploadedFile::fake()->image('final.jpg', 1600, 900);

    Native::visit("/posts/{$this->post->id}/cover")
        ->call('coverChosen', $first->getPathname())
        ->call('save');
    $saved = $this->post->fresh()->cover['path'];

    $screen = Native::visit("/posts/{$this->post->id}/cover")
        ->call('coverChosen', $draft->getPathname());
    $draftPath = $screen->get('cover')->path;

    $screen->call('coverChosen', $final->getPathname());
    $finalPath = $screen->get('cover')->path;

    $disk = Storage::disk('mobile_public');
    $disk->assertMissing($draftPath);
    $disk->assertExists($saved);

    $screen->call('save');
    $disk->assertMissing($saved);
    $disk->assertExists($finalPath);
    expect($this->post->fresh()->cover['path'])->toBe($finalPath);
});

it('removes the cover only when the cleared field is saved', function () {
    $photo = UploadedFile::fake()->image('beach.jpg', 1600, 900);

    Native::visit("/posts/{$this->post->id}/cover")
        ->call('coverChosen', $photo->getPathname())
        ->call('save');
    $saved = $this->post->fresh()->cover['path'];

    $screen = Native::visit("/posts/{$this->post->id}/cover")
        ->assertSet('cover.path', $saved)
        ->call('coverCleared');
    Storage::disk('mobile_public')->assertExists($saved);

    $screen->call('save');
    Storage::disk('mobile_public')->assertMissing($saved);
    expect($this->post->fresh()->cover)->toBeNull();
});
