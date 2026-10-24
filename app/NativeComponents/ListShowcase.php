<?php

namespace App\NativeComponents;

use FirstlightUI\Pagination\ListPage;
use Illuminate\Auth\GenericUser;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\View\View;

class ListShowcase extends ShowcaseScreen
{
    public int $pressCount = 0;

    public string $lastPressed = 'None';

    public int $refreshCount = 0;

    public bool $plain = false;

    public bool $separators = true;

    public bool $empty = false;

    public bool $deletionAllowed = true;

    public bool $testOptionsVisible = false;

    /** @var list<int> */
    public array $deletedIds = [];

    public function mount(): void
    {
        $this->refreshList($this->samplePage(...));
    }

    public function recordAccount(): void
    {
        $this->record('Account');
    }

    public function recordNotifications(): void
    {
        $this->record('Notifications');
    }

    public function recordPrivacy(): void
    {
        $this->record('Privacy');
    }

    public function reloadItems(): void
    {
        $this->refreshCount++;
        $this->refreshList($this->samplePage(...));
    }

    public function loadMore(): void
    {
        if (! $this->empty) {
            $this->loadMoreList($this->samplePage(...));
        }
    }

    public function showTestOptions(): void
    {
        $this->testOptionsVisible = true;
    }

    public function closeTestOptions(): void
    {
        $this->testOptionsVisible = false;
    }

    public function resetSampleData(): void
    {
        $this->deletedIds = [];
        $this->empty = false;
        $this->refreshList($this->samplePage(...));
    }

    public function requestDeletion(int $id): void
    {
        $this->requestDestructiveListAction($id, 'showcase-delete');
    }

    public function deleteSample(): void
    {
        $this->confirmDestructiveListAction(
            destroy: function (array $item): void {
                $this->deletedIds[] = $item['id'];
            },
            ability: 'showcase-delete',
            successMessage: 'Sample row removed',
        );
    }

    protected function gateUser(): GenericUser
    {
        return new GenericUser(['id' => 'sample-tester', 'may_delete' => $this->deletionAllowed]);
    }

    private function samplePage(ListPage $page): LengthAwarePaginator
    {
        // Keep sample page boundaries stable when an earlier row is deleted.
        $items = collect(range(1001, 1024))
            ->forPage($page->page, 8)
            ->reject(fn (int $id): bool => in_array($id, $this->deletedIds, true))
            ->map(fn (int $id): array => ['id' => $id, 'label' => 'Sample item '.$id])
            ->values();

        return new LengthAwarePaginator($items, 24, 8, $page->page);
    }

    public function navTitle(): string
    {
        return 'Firstlight List';
    }

    public function render(): View
    {
        return view('native.list-showcase');
    }

    private function record(string $headline): void
    {
        $this->pressCount++;
        $this->lastPressed = $headline;
    }
}
