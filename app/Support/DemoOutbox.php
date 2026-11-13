<?php

namespace App\Support;

use FirstlightUI\Connectivity\PendingChanges;
use FirstlightUI\Facades\Connectivity;
use Illuminate\Support\Facades\Cache;
use Native\Mobile\AsyncTask;

/**
 * A pretend write queue for the Offline changes recipe, kept in the cache so
 * the background drain sees it too. Real apps keep their own queue; Firstlight
 * only reads these counts and asks for a drain when the phone is back online.
 */
class DemoOutbox implements PendingChanges
{
    public const TASKS = 'recipes.offline.tasks';

    public const QUEUE = 'recipes.offline.queue';

    /** A drain that has not finished after this long is treated as over, so "Syncing…" can't stick. */
    private const SYNC_TIMEOUT = 30;

    /** @var array<int, array{title: string, done: bool}> */
    private const SEED = [
        1 => ['title' => 'Check the backup report', 'done' => false],
        2 => ['title' => 'Rotate the API key', 'done' => true],
        3 => ['title' => 'Reply to the support ticket', 'done' => false],
        4 => ['title' => 'Renew the certificate', 'done' => false],
    ];

    /** @return array<int, array{title: string, done: bool}> The tasks as the server has them. */
    public static function tasks(): array
    {
        return Cache::get(self::TASKS, self::SEED);
    }

    /** @return array{changes: array<int, array{done: bool, state: string}>, syncingSince: ?int, failNext: bool} */
    public static function queue(): array
    {
        return Cache::get(self::QUEUE, ['changes' => [], 'syncingSince' => null, 'failNext' => false]);
    }

    /** Queue a change to send later. A newer change to the same task replaces the older one. */
    public static function queueChange(int $task, bool $done): void
    {
        $queue = self::queue();
        $queue['changes'][$task] = ['done' => $done, 'state' => 'waiting'];
        Cache::forever(self::QUEUE, $queue);
    }

    /** Send a change now, as the app does while online. */
    public static function send(int $task, bool $done): void
    {
        $tasks = self::tasks();
        $tasks[$task]['done'] = $done;
        Cache::forever(self::TASKS, $tasks);

        $queue = self::queue();
        unset($queue['changes'][$task]);
        Cache::forever(self::QUEUE, $queue);
    }

    public static function failNextSync(bool $fail): void
    {
        $queue = self::queue();
        $queue['failNext'] = $fail;
        Cache::forever(self::QUEUE, $queue);
    }

    public static function reset(): void
    {
        Cache::forget(self::TASKS);
        Cache::forget(self::QUEUE);
    }

    public function waitingCount(): int
    {
        return count(array_filter(self::queue()['changes'], fn (array $change): bool => $change['state'] === 'waiting'));
    }

    public function failedCount(): int
    {
        return count(array_filter(self::queue()['changes'], fn (array $change): bool => $change['state'] === 'failed'));
    }

    public function isSyncing(): bool
    {
        $since = self::queue()['syncingSince'];

        return $since !== null && time() - $since < self::SYNC_TIMEOUT;
    }

    /** Sends the waiting changes on a background thread, taking a couple of seconds as a real API would. */
    public function sync(): void
    {
        if ($this->isSyncing() || $this->waitingCount() === 0) {
            return;
        }

        $queue = self::queue();
        $queue['syncingSince'] = time();
        Cache::forever(self::QUEUE, $queue);

        AsyncTask::dispatch(static function (): array {
            sleep(2);

            return DemoOutbox::drain();
        })
            ->timeout(self::SYNC_TIMEOUT)
            ->finished(function (array $result): void {
                Connectivity::syncFinished($result['synced'], $result['failed']);
            })
            ->failed(function (): void {
                DemoOutbox::drain();
            });
    }

    /**
     * Apply the waiting changes to the tasks. With "Fail the next sync" on,
     * the first change fails instead, as a server would refuse it.
     *
     * @return array{synced: int, failed: int}
     */
    public static function drain(): array
    {
        $queue = self::queue();
        $tasks = self::tasks();
        $synced = $failed = 0;

        foreach ($queue['changes'] as $task => $change) {
            if ($change['state'] !== 'waiting') {
                continue;
            }

            if ($queue['failNext']) {
                $queue['changes'][$task]['state'] = 'failed';
                $queue['failNext'] = false;
                $failed++;

                continue;
            }

            $tasks[$task]['done'] = $change['done'];
            unset($queue['changes'][$task]);
            $synced++;
        }

        $queue['syncingSince'] = null;
        Cache::forever(self::TASKS, $tasks);
        Cache::forever(self::QUEUE, $queue);

        return ['synced' => $synced, 'failed' => $failed];
    }

    /** Put failed changes back in line to be sent again. */
    public static function retryFailed(): void
    {
        $queue = self::queue();

        foreach ($queue['changes'] as $task => $change) {
            $queue['changes'][$task]['state'] = 'waiting';
        }

        Cache::forever(self::QUEUE, $queue);
    }
}
