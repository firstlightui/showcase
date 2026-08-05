<?php

use App\Support\ShowcaseFeedbackLog;
use FirstlightUI\Feedback\FeedbackStore;
use FirstlightUI\Feedback\FeedbackTone;
use Native\Mobile\Testing\Native;

beforeEach(function () {
    app(FeedbackStore::class)->reset();
    app(ShowcaseFeedbackLog::class)->reset();
});

afterEach(function () {
    app(FeedbackStore::class)->reset();
    app(ShowcaseFeedbackLog::class)->reset();
});

it('publishes stable transient feedback capture content', function () {
    $screen = Native::visit('/captures/transient-feedback');
    $screen->assertSee('Stable capture fixture')->assertAccessible();
    $items = app(FeedbackStore::class)->all();

    expect($screen->tree()['props']['title'] ?? null)->toBe('Firstlight Transient Feedback')
        ->and($screen->tree()['props']['back'] ?? null)->toBeFalse()
        ->and($items)->toHaveCount(1)
        ->and($items[0]->message)->toBe('Appointment saved')
        ->and($items[0]->id)->toBe('transient-feedback-capture')
        ->and($items[0]->tone)->toBe(FeedbackTone::Success)
        ->and($items[0]->actionLabel)->toBe('Undo')
        ->and($items[0]->actionKey)->toBe('undo-save')
        ->and($items[0]->hold)->toBeTrue();
});
