<?php

namespace App\NativeComponents;

use Illuminate\View\View;
use Native\Mobile\UI\Theme;

class BadgeShowcase extends ShowcaseScreen
{
    /** Firstlight's fallback warning pair, used when the theme sets none. */
    private const DEFAULT_WARNING = [
        'light' => ['warning' => '#B45309', 'on-warning' => '#FFFFFF'],
        'dark' => ['warning' => '#FBBF24', 'on-warning' => '#1C1917'],
    ];

    private const CUSTOM_WARNING = [
        'light' => ['warning' => '#7C2D12', 'on-warning' => '#FFFFFF'],
        'dark' => ['warning' => '#FDBA74', 'on-warning' => '#1C1917'],
    ];

    public int $unreadCount = 3;

    public bool $prescriptionReady = false;

    public bool $customWarning = false;

    public function addUnread(): void
    {
        $this->unreadCount += 49;
    }

    public function clearUnread(): void
    {
        $this->unreadCount = 0;
    }

    public function togglePrescription(): void
    {
        $this->prescriptionReady = ! $this->prescriptionReady;
    }

    public function applyCustomWarning(): void
    {
        Theme::merge(self::CUSTOM_WARNING);
        $this->customWarning = true;
    }

    public function restoreWarning(): void
    {
        Theme::merge(self::DEFAULT_WARNING);
        $this->customWarning = false;
    }

    public function navTitle(): string
    {
        return 'Firstlight Badge';
    }

    public function render(): View
    {
        return view('native.badge-showcase');
    }
}
