<?php

use App\NativeComponents\TransientFeedbackShowcase;
use App\Support\ShowcaseFeedbackLog;
use FirstlightUI\Events\FeedbackActionPressed;
use FirstlightUI\Events\FeedbackDismissed;
use FirstlightUI\Feedback\FeedbackDismissReason;
use FirstlightUI\Feedback\FeedbackStore;
use FirstlightUI\Feedback\FeedbackTone;
use Native\Mobile\Testing\Native;

/** @return list<array<string, mixed>> */
function transientFeedbackShowcaseNodesOfType(array $tree, string $type): array
{
    $nodes = ($tree['type'] ?? null) === $type ? [$tree] : [];

    foreach ($tree['children'] ?? [] as $child) {
        $nodes = [...$nodes, ...transientFeedbackShowcaseNodesOfType($child, $type)];
    }

    return $nodes;
}

beforeEach(function () {
    app(FeedbackStore::class)->reset();
    app(ShowcaseFeedbackLog::class)->reset();
});

afterEach(function () {
    app(FeedbackStore::class)->reset();
    app(ShowcaseFeedbackLog::class)->reset();
});

it('registers Transient Feedback on the showcase home screen', function () {
    $screen = Native::visit('/');
    $items = transientFeedbackShowcaseNodesOfType($screen->tree(), 'list_item');
    $transientFeedback = collect($items)->first(
        fn (array $item): bool => ($item['props']['headline'] ?? null) === 'Transient Feedback',
    );

    expect($transientFeedback)->not->toBeNull()
        ->and($transientFeedback['props']['overline'])->toBe('Transient Feedback');
});

it('publishes the complete interactive demonstration through its native route', function () {
    Native::visit('/transient-feedback')
        ->assertSee('Message-only tones')
        ->assertSee('Show neutral')
        ->assertSee('Show success')
        ->assertSee('Show warning')
        ->assertSee('Show danger')
        ->assertSee('Show Undo action')
        ->assertSee('Hold until dismissed')
        ->assertSee('Remove held feedback')
        ->assertSee('Queue three in FIFO order')
        ->assertSee('Update one stable ID')
        ->assertSee('Show feedback and navigate')
        ->assertSee('Most recent feedback event')
        ->assertAccessible();
});

it('publishes each semantic tone and the action demonstration', function () {
    $screen = Native::test(TransientFeedbackShowcase::class);
    $store = app(FeedbackStore::class);

    $screen->call('publishDefault');
    expect($store->all())->toHaveCount(1)
        ->and($store->all()[0]->message)->toBe('Neutral information')
        ->and($store->all()[0]->tone)->toBe(FeedbackTone::Default)
        ->and($store->all()[0]->actionLabel)->toBeNull();

    $store->reset();
    $screen->call('publishSuccess');
    expect($store->all()[0]->message)->toBe('Appointment saved')
        ->and($store->all()[0]->tone)->toBe(FeedbackTone::Success);

    $store->reset();
    $screen->call('publishWarning');
    expect($store->all()[0]->message)->toBe('Check the appointment details')
        ->and($store->all()[0]->tone)->toBe(FeedbackTone::Warning);

    $store->reset();
    $screen->call('publishDanger');
    expect($store->all()[0]->message)->toBe('Unable to save appointment')
        ->and($store->all()[0]->tone)->toBe(FeedbackTone::Danger);

    $store->reset();
    $screen->call('publishAction');
    expect($store->all())->toHaveCount(1)
        ->and($store->all()[0]->id)->toBe('feedback-action')
        ->and($store->all()[0]->actionLabel)->toBe('Undo')
        ->and($store->all()[0]->actionKey)->toBe('undo-save');
});

it('publishes held feedback and removes it programmatically without an event', function () {
    $screen = Native::test(TransientFeedbackShowcase::class)
        ->call('publishHeld');
    $items = app(FeedbackStore::class)->all();

    expect($items)->toHaveCount(1)
        ->and($items[0]->id)->toBe('held-feedback')
        ->and($items[0]->hold)->toBeTrue();

    $screen->call('removeHeld')
        ->assertSet('lastRemovalSucceeded', true);

    expect(app(FeedbackStore::class)->all())->toBeEmpty()
        ->and(app(ShowcaseFeedbackLog::class)->latest())->toBe('No feedback events yet');
});

it('queues the fifo demonstration in authored order', function () {
    Native::test(TransientFeedbackShowcase::class)
        ->call('queueThree');

    expect(array_map(
        fn ($item) => $item->message,
        app(FeedbackStore::class)->all(),
    ))->toBe(['First queued', 'Second queued', 'Third queued']);
});

it('updates a duplicate stable id in place', function () {
    Native::test(TransientFeedbackShowcase::class)
        ->call('queueStableUpdate');

    $items = app(FeedbackStore::class)->all();

    expect(array_map(fn ($item) => $item->id, $items))->toBe([
        'stable-update',
        'stable-update-follower',
    ])->and(array_map(fn ($item) => $item->message, $items))->toBe([
        'Updated in place',
        'Still second',
    ])->and($items[0]->tone)->toBe(FeedbackTone::Warning)
        ->and($items[0]->hold)->toBeTrue();
});

it('keeps held feedback queued while navigating to the dedicated destination', function () {
    Native::test(TransientFeedbackShowcase::class)
        ->call('navigateWithFeedback')
        ->assertNavigatedTo('/transient-feedback/destination');

    $items = app(FeedbackStore::class)->all();

    expect($items)->toHaveCount(1)
        ->and($items[0]->id)->toBe('navigation-feedback')
        ->and($items[0]->hold)->toBeTrue();

    Native::visit('/transient-feedback/destination')
        ->assertSee('Feedback survives navigation')
        ->call('removeNavigationFeedback')
        ->assertSet('lastRemovalSucceeded', true);

    expect(app(FeedbackStore::class)->all())->toBeEmpty();
});

it('records the exact most recent action and dismissal event payloads', function () {
    $log = app(ShowcaseFeedbackLog::class);

    event(new FeedbackActionPressed('feedback-action', 'undo-save'));
    expect($log->latest())->toBe('Action pressed · id=feedback-action · actionKey=undo-save');
    Native::visit('/transient-feedback')
        ->assertSee('Action pressed · id=feedback-action · actionKey=undo-save');

    event(new FeedbackDismissed('held-feedback', FeedbackDismissReason::Manual));
    expect($log->latest())->toBe('Dismissed · id=held-feedback · reason=manual');
    Native::visit('/transient-feedback/destination')
        ->assertSee('Dismissed · id=held-feedback · reason=manual');
});
