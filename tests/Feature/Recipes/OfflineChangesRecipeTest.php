<?php

use App\Support\DemoOutbox;
use Carbon\CarbonImmutable;
use FirstlightUI\Connectivity\ConnectivityManager;
use FirstlightUI\Facades\Connectivity;
use FirstlightUI\Facades\Feedback;
use FirstlightUI\Feedback\FeedbackRecord;
use FirstlightUI\Feedback\FeedbackTone;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Native\Mobile\AsyncTask;
use Native\Mobile\Testing\Native;
use Native\Mobile\Testing\TestableComponent;

uses(RefreshDatabase::class);

beforeEach(function () {
    Cache::flush();
    AsyncTask::fake();
    $this->feedback = Feedback::fake();
    $this->connectivity = Connectivity::fake();
});

function offlineChangesSubtitle(TestableComponent $screen): ?string
{
    return $screen->tree()['props']['subtitle'] ?? null;
}

/** @return array<string, ?string> Each task's headline and its trailing sync status. */
function offlineChangesRows(TestableComponent $screen): array
{
    $rows = [];
    $walk = function (array $node) use (&$walk, &$rows): void {
        if (($node['type'] ?? null) === 'firstlight.list-item') {
            $rows[$node['props']['headline']] = ($node['props']['trailing_type'] ?? null) === 'status' ? $node['props']['trailing_value'] : null;
        }

        foreach ($node['children'] ?? [] as $child) {
            $walk($child);
        }
    };
    $walk($screen->tree());

    return $rows;
}

it('sends a change at once while online', function () {
    $screen = Native::visit('/offline-changes')->assertAccessible()->tap('Check the backup report');

    expect(DemoOutbox::tasks()[1]['done'])->toBeTrue()
        ->and(DemoOutbox::queue()['changes'])->toBe([])
        ->and(offlineChangesSubtitle($screen))->toBeNull();
});

it('queues changes while offline and syncs them once the phone is back online', function () {
    $this->connectivity->goOffline();
    $screen = Native::visit('/offline-changes')
        ->tap('Check the backup report')
        ->tap('Renew the certificate');

    expect(offlineChangesSubtitle($screen))->toBe('Offline · 2 waiting to sync')
        ->and(offlineChangesRows($screen))->toMatchArray([
            'Check the backup report' => 'Waiting to sync',
            'Renew the certificate' => 'Waiting to sync',
            'Rotate the API key' => null,
        ])
        ->and(DemoOutbox::tasks()[1]['done'])->toBeFalse();
    $screen->assertAccessible()->assertDontSee('Sync now');

    $this->connectivity->goOnline();

    expect(DemoOutbox::tasks()[1]['done'])->toBeTrue()
        ->and(DemoOutbox::tasks()[4]['done'])->toBeTrue()
        ->and($this->feedback->sent()->map(fn (FeedbackRecord $record) => $record->message)->all())->toBe(['Synced 2 changes.']);

    $screen = Native::visit('/offline-changes');
    expect(offlineChangesSubtitle($screen))->toBeNull()
        ->and(array_filter(offlineChangesRows($screen)))->toBe([]);
});

it('shows a change the server refused until it is sent again', function () {
    $this->connectivity->goOffline();
    Native::visit('/offline-changes')->set('failNextSync', true)->tap('Check the backup report');

    $this->connectivity->goOnline();

    $screen = Native::visit('/offline-changes');
    expect(offlineChangesSubtitle($screen))->toBe('1 failed to sync')
        ->and(offlineChangesRows($screen)['Check the backup report'])->toBe('Failed to sync')
        ->and($this->feedback->sent()->map(fn (FeedbackRecord $record) => [$record->tone, $record->message, $record->actionKey])->all())
        ->toBe([[FeedbackTone::Warning, '1 change failed to sync.', ConnectivityManager::REVIEW_ACTION]]);

    $screen->assertSee('Sync now')->tap('Sync now');
    expect(DemoOutbox::tasks()[1]['done'])->toBeTrue()
        ->and(offlineChangesSubtitle($screen))->toBeNull();
});

it('refreshes stale server status when the phone is back online', function () {
    CarbonImmutable::setTestNow('2026-11-13 14:05:00');
    $screen = Native::visit('/servers')->call('age', 15);

    $this->connectivity->goOffline()->goOnline();

    expect((fn () => $this->freshnessNotice('servers'))->call($screen->instance()))->toBeNull()
        ->and(Cache::get('recipes.server-status')['fetched_at'])->toBe('2026-11-13T14:05:00+00:00');

    CarbonImmutable::setTestNow();
});
