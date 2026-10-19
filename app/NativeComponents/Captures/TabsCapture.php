<?php

namespace App\NativeComponents\Captures;

use Illuminate\View\View;
use Native\Mobile\Edge\NativeComponent;

class TabsCapture extends NativeComponent
{
    public string $tab = 'deploys';

    public string $siteTab = 'overview';

    /** @var list<array<string, mixed>> */
    public array $serverTabs = [
        ['value' => 'overview', 'label' => 'Overview'],
        ['value' => 'sites', 'label' => 'Sites'],
        ['value' => 'deploys', 'label' => 'Deployments', 'badge' => 2, 'badge-a11y-label' => '2 failed'],
        ['value' => 'manage', 'label' => 'Manage', 'disabled' => true],
    ];

    /** @var array<string, string> */
    public array $siteTabs = [
        'overview' => 'Overview',
        'deploys' => 'Deploys',
        'env' => 'Env',
    ];

    public function navTitle(): string
    {
        return 'Firstlight Tabs';
    }

    public function render(): View
    {
        return view('native.captures.tabs');
    }
}
