<?php

namespace App\NativeComponents;

use Illuminate\View\View;

class TabsShowcase extends ShowcaseScreen
{
    public string $tab = 'overview';

    public string $guardedTab = 'overview';

    public int $guardedAttempts = 0;

    public ?string $unselected = null;

    public string $disabledTab = 'sites';

    public string $longTab = 'deploys';

    public int $failedDeployments = 2;

    /** @var array<string, string> */
    public array $simpleTabs = [
        'overview' => 'Overview',
        'sites' => 'Sites',
        'deploys' => 'Deployments',
        'manage' => 'Manage',
    ];

    /** @var list<array<string, mixed>> */
    public array $longTabs = [
        ['value' => 'overview', 'label' => 'Overview'],
        ['value' => 'deploys', 'label' => 'Deployments', 'badge' => 140, 'badge-a11y-label' => 'more than 99 queued'],
        ['value' => 'environment', 'label' => 'Environment'],
        ['value' => 'scheduler', 'label' => 'Scheduler'],
        ['value' => 'manage', 'label' => 'Manage'],
    ];

    /** @var list<array<string, string>> */
    public array $emptyTabs = [];

    /** @return list<array<string, mixed>> */
    public function serverTabs(): array
    {
        return [
            ['value' => 'overview', 'label' => 'Overview'],
            ['value' => 'sites', 'label' => 'Sites'],
            [
                'value' => 'deploys',
                'label' => 'Deployments',
                'badge' => $this->failedDeployments,
                'badge-a11y-label' => "{$this->failedDeployments} failed",
            ],
            ['value' => 'manage', 'label' => 'Manage', 'disabled' => true],
        ];
    }

    public function showDeployments(): void
    {
        $this->tab = 'deploys';
    }

    public function failAnotherDeployment(): void
    {
        $this->failedDeployments++;
    }

    public function clearFailures(): void
    {
        $this->failedDeployments = 0;
    }

    public function selectGuardedTab(string $tab): void
    {
        $this->guardedAttempts++;

        if ($tab !== 'manage') {
            $this->guardedTab = $tab;
        }
    }

    public function navTitle(): string
    {
        return 'Firstlight Tabs';
    }

    public function render(): View
    {
        return view('native.tabs-showcase', ['serverSections' => $this->serverTabs()]);
    }
}
