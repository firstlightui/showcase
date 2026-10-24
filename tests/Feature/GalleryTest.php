<?php

use App\NativeComponents\GalleryInboxScreen;
use App\NativeComponents\GalleryScreen;
use Carbon\CarbonImmutable;
use FirstlightUI\Feedback\FeedbackStore;
use FirstlightUI\Feedback\FeedbackTone;
use Native\Mobile\Testing\Native;

/** @return array<string, array<string, mixed>> Props of the first node of each type. */
function galleryNodes(array $tree, string $type): array
{
    $nodes = ($tree['type'] ?? null) === $type ? [$tree['props']] : [];

    foreach ($tree['children'] ?? [] as $child) {
        $nodes = [...$nodes, ...galleryNodes($child, $type)];
    }

    return $nodes;
}

/** @return array<string, array<string, mixed>> */
function galleryMetrics(array $tree): array
{
    return collect(galleryNodes($tree, 'firstlight.metric'))->keyBy('label')->all();
}

function galleryButton(array $tree): array
{
    return galleryNodes($tree, 'firstlight.button')[0];
}

beforeEach(function () {
    // Wednesday 21 October 2026 in Sydney: the default lesson is Saturday the 24th.
    CarbonImmutable::setTestNow(CarbonImmutable::parse('2026-10-21 09:30', GalleryScreen::TIMEZONE));
    app(FeedbackStore::class)->reset();
});

afterEach(function () {
    CarbonImmutable::setTestNow();
    app(FeedbackStore::class)->reset();
});

it('opens on a bookable Saturday dawn session with a live total', function () {
    $screen = Native::visit('/')->assertAccessible();
    $metrics = galleryMetrics($screen->tree());
    $date = galleryNodes($screen->tree(), 'firstlight.date-picker')[0];

    expect($screen->get('date'))->toBe('2026-10-24')
        ->and($date['min'])->toBe('2026-10-21')
        ->and($date['max'])->toBe('2026-11-17')
        ->and($metrics['Boards left'])->toMatchArray(['value' => '3 of 12', 'detail' => 'Sat 24 Oct, 6:00 am', 'tone' => 'warning'])
        ->and($metrics['Total'])->toMatchArray(['value' => '$120', 'detail' => '2 riders with hire'])
        ->and(galleryButton($screen->tree()))->toMatchArray(['label' => 'Book 2 riders · $120', 'disabled' => false]);
});

it('recalculates the total and availability from every control', function () {
    $screen = Native::visit('/')
        ->set('riders', 3)
        ->set('hire', false)
        ->set('session', '07:30');
    $metrics = galleryMetrics($screen->tree());

    expect($metrics['Total'])->toMatchArray(['value' => '$135', 'detail' => '3 riders'])
        ->and($metrics['Boards left'])->toMatchArray(['value' => '8 of 12', 'tone' => 'success'])
        ->and(galleryButton($screen->tree())['label'])->toBe('Book 3 riders · $135');

    $session = galleryNodes($screen->tree(), 'firstlight.segmented')[0];
    expect(array_combine($session['option_values'], $session['option_enabled']))
        ->toBe(['06:00' => '1', '07:30' => '1', '09:00' => '0']);
});

it('blocks a booking larger than the boards left and says why', function () {
    $screen = Native::visit('/')->set('riders', 4);
    $stepper = galleryNodes($screen->tree(), 'firstlight.stepper')[0];

    expect($stepper['error'])->toBe('Only 3 boards left in this session.')
        ->and(galleryButton($screen->tree()))->toMatchArray(['label' => 'Not enough boards', 'disabled' => true]);

    $screen->call('book');
    expect($screen->get('bookings'))->toBe([])
        ->and(app(FeedbackStore::class)->all())->toBe([]);
});

it('books boards, confirms with feedback and restores them on Undo', function () {
    $screen = Native::visit('/')->tap('Book 2 riders · $120');
    $feedback = app(FeedbackStore::class)->all();

    expect($feedback)->toHaveCount(1)
        ->and($feedback[0]->tone)->toBe(FeedbackTone::Success)
        ->and($feedback[0]->message)->toBe('Booked Sat 24 Oct, 6:00 am for 2 riders')
        ->and($feedback[0]->actionLabel)->toBe('Undo')
        ->and(galleryMetrics($screen->tree())['Boards left']['value'])->toBe('1 of 12')
        ->and(galleryNodes($screen->tree(), 'firstlight.status-label')[0]['label'])->toBe('Booked for 2 riders')
        // Riders shrink to the boards that remain, so the next booking stays valid.
        ->and($screen->get('riders'))->toBe(1)
        ->and(galleryNodes($screen->tree(), 'firstlight.stepper')[0]['error'] ?? '')->toBe('')
        ->and(galleryButton($screen->tree()))->toMatchArray(['label' => 'Book 1 rider · $60', 'disabled' => false]);

    // A second session on the same day is independent.
    $screen->set('session', '07:30');
    expect(galleryMetrics($screen->tree())['Boards left']['value'])->toBe('8 of 12');

    $screen->set('session', '06:00')->fireEvent('action', 1);

    expect($screen->get('bookings'))->toBe([])
        ->and(galleryMetrics($screen->tree())['Boards left']['value'])->toBe('3 of 12')
        ->and(galleryButton($screen->tree())['disabled'])->toBeFalse();
});

it('pushes the inbox vignette from the gallery', function () {
    Native::visit('/')->tap('Inbox')->assertNavigatedTo('/gallery/inbox');

    Native::visit('/gallery/inbox')
        ->assertNavTitle('Inbox')
        ->assertAccessible();
});

it('triages the inbox with filters, swipe actions and the context menu', function () {
    $screen = Native::test(GalleryInboxScreen::class);
    $labels = fn (): array => array_column(galleryNodes($screen->tree(), 'firstlight.status-label'), 'label');
    $rows = fn (): array => collect(galleryNodes($screen->tree(), 'firstlight.list-item'))->keyBy('headline')->all();

    expect($labels())->toBe(['3 unread', '2 flagged'])
        ->and($rows()['Maya Chen'])->toMatchArray(['trailing_value' => 'circle', 'trailing_icon_tone' => 'info'])
        ->and($rows()['Maya Chen']['a11y_label'])->toBe('Maya Chen, Board fitting moved to 7:30, 8:12 am, unread')
        ->and($rows()['Tom Okafor'])->toMatchArray(['trailing_type' => 'text', 'trailing_value' => '6:40 am']);

    // Pressing a row reads it; the leading swipe toggles it back.
    $screen->call('open', 1);
    expect($rows()['Maya Chen'])->toMatchArray(['trailing_type' => 'text', 'trailing_value' => '8:12 am'])
        ->and($labels())->toBe(['2 unread', '2 flagged']);
    $screen->call('toggleRead', 1);
    expect($labels())->toBe(['3 unread', '2 flagged']);

    $screen->call('toggleFlag', 3)->set('filter', 'flagged');
    expect(array_keys($rows()))->toBe(['Bondi Surf Co.', 'Tom Okafor', 'Lifeguard roster']);

    $screen->set('filter', 'unread');
    expect(array_keys($rows()))->toBe(['Maya Chen', 'Bondi Surf Co.', 'Priya Raman']);
});

it('archives with Undo and deletes only after confirmation', function () {
    $screen = Native::test(GalleryInboxScreen::class)->call('archive', 2);
    $headlines = fn (): array => array_column(galleryNodes($screen->tree(), 'firstlight.list-item'), 'headline');

    expect($headlines())->not->toContain('Bondi Surf Co.')
        ->and(app(FeedbackStore::class)->all()[0]->message)->toBe('Archived “Your lesson receipt”');

    $screen->fireEvent('action', 1);
    expect($headlines())->toBe(['Maya Chen', 'Bondi Surf Co.', 'Tom Okafor', 'Priya Raman', 'Lifeguard roster', 'Firstlight team']);

    $screen->call('requestDelete', 4);
    expect(galleryNodes($screen->tree(), 'firstlight.confirmation-dialog')[0])->toMatchArray([
        'visible' => true,
        'message' => '“Carpool for the dawn session?” will be removed. Archive keeps a copy.',
    ]);

    $screen->call('cancelDelete');
    expect($headlines())->toContain('Priya Raman');

    $screen->call('requestDelete', 4)->call('deleteMessage');
    expect($headlines())->not->toContain('Priya Raman')
        ->and(galleryNodes($screen->tree(), 'firstlight.confirmation-dialog')[0]['visible'])->toBeFalse();
});

it('shows an empty state instead of an empty list', function () {
    $screen = Native::test(GalleryInboxScreen::class);
    foreach ([1, 2, 3, 4, 5, 6] as $id) {
        $screen->call('archive', $id);
    }

    expect(galleryNodes($screen->tree(), 'firstlight.list-item'))->toBe([])
        ->and(galleryNodes($screen->tree(), 'firstlight.callout')[0]['message'])->toBe('Inbox zero. Undo brings an archived message back.')
        ->and(array_column(galleryNodes($screen->tree(), 'firstlight.status-label'), 'label'))->toBe(['All caught up']);

    $screen->assertAccessible();
});
