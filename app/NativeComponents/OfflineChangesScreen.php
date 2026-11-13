<?php

namespace App\NativeComponents;

use App\Support\DemoOutbox;
use FirstlightUI\Connectivity\ConnectivityManager;
use FirstlightUI\Events\FeedbackActionPressed;
use FirstlightUI\Facades\Connectivity;
use FirstlightUI\NativeComponent;
use Illuminate\View\View;
use Native\Mobile\Attributes\On;

/**
 * A task list whose changes queue while the phone is offline. The nav bar
 * subtitle and the rows show the queue; coming back online sends it.
 */
class OfflineChangesScreen extends NativeComponent
{
    public bool $failNextSync = false;

    public function mount(): void
    {
        $this->failNextSync = DemoOutbox::queue()['failNext'];
    }

    public function toggle(int $task): void
    {
        $queued = DemoOutbox::queue()['changes'][$task] ?? null;
        $done = ! ($queued['done'] ?? DemoOutbox::tasks()[$task]['done']);

        if (Connectivity::isOffline()) {
            DemoOutbox::queueChange($task, $done);
        } else {
            DemoOutbox::send($task, $done);
        }
    }

    public function updatedFailNextSync(bool $value): void
    {
        DemoOutbox::failNextSync($value);
    }

    public function syncNow(): void
    {
        if (Connectivity::isOffline()) {
            return;
        }

        DemoOutbox::retryFailed();
        Connectivity::pending()?->sync();
    }

    public function startOver(): void
    {
        DemoOutbox::reset();
        $this->failNextSync = false;
    }

    #[On(FeedbackActionPressed::class)]
    public function feedbackAction(string $id, string $actionKey): void
    {
        // The failed-sync Feedback's Review action. A real app opens its pending screen.
        if ($actionKey === ConnectivityManager::REVIEW_ACTION) {
            DemoOutbox::retryFailed();
        }
    }

    public function navTitle(): string
    {
        return 'Offline changes';
    }

    public function render(): View
    {
        $queue = DemoOutbox::queue();

        $tasks = [];
        foreach (DemoOutbox::tasks() as $id => $task) {
            $change = $queue['changes'][$id] ?? null;
            $tasks[$id] = [
                'title' => $task['title'],
                'done' => $change['done'] ?? $task['done'],
                'state' => $change['state'] ?? null,
            ];
        }

        return view('native.offline-changes', [
            'tasks' => $tasks,
            'syncing' => app(DemoOutbox::class)->isSyncing(),
            'hasQueued' => $queue['changes'] !== [],
            'offline' => Connectivity::isOffline(),
        ]);
    }
}
