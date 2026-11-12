<?php

namespace App\NativeComponents;

use Illuminate\View\View;

class StatusLabelShowcase extends ShowcaseScreen
{
    /** @var list<array{label: string, tone: string, icon: ?string, ios: ?string}> */
    private const CLAIM_STATES = [
        ['label' => 'Claim drafted', 'tone' => 'neutral', 'icon' => null, 'ios' => null],
        ['label' => 'Claim submitted', 'tone' => 'info', 'icon' => 'info', 'ios' => 'info.circle'],
        ['label' => 'Claim paid', 'tone' => 'success', 'icon' => 'check_circle', 'ios' => 'checkmark.circle'],
        ['label' => 'Claim rejected', 'tone' => 'danger', 'icon' => 'error', 'ios' => 'xmark.circle'],
    ];

    public int $claimStep = 0;

    public function advanceClaim(): void
    {
        $this->claimStep = ($this->claimStep + 1) % count(self::CLAIM_STATES);
    }

    /** @return array{label: string, tone: string, icon: ?string, ios: ?string} */
    public function claimStatus(): array
    {
        return self::CLAIM_STATES[$this->claimStep];
    }

    public function navTitle(): string
    {
        return 'Firstlight Status Label';
    }

    public function render(): View
    {
        return view('native.status-label-showcase');
    }
}
