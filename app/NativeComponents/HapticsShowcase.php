<?php

namespace App\NativeComponents;

use FirstlightUI\Facades\Feedback;
use FirstlightUI\Facades\Haptics;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

/**
 * Every haptic in Firstlight's vocabulary, played natively as each button's
 * tap lands, plus the ones PHP plays: Feedback outcomes, impacts and the
 * install-wide switch.
 */
class HapticsShowcase extends ShowcaseScreen
{
    /** @var array<string, array<string, string>> Haptic value => button label, by group. */
    public const GROUPS = [
        'Outcomes' => ['success' => 'Success', 'warning' => 'Warning', 'error' => 'Error'],
        'Selection and toggles' => ['selection' => 'Selection', 'tick' => 'Tick', 'toggle-on' => 'Toggle on', 'toggle-off' => 'Toggle off'],
        'Presses and gestures' => ['press' => 'Press', 'keypress' => 'Keypress', 'long-press' => 'Long press', 'drag-start' => 'Drag start', 'threshold-activate' => 'Threshold on', 'threshold-deactivate' => 'Threshold off'],
        'Changes' => ['increase' => 'Increase', 'decrease' => 'Decrease', 'alignment' => 'Alignment', 'level-change' => 'Level change', 'start' => 'Start', 'stop' => 'Stop', 'path-complete' => 'Path complete'],
    ];

    /** @var list<string> */
    public const IMPACTS = ['light', 'medium', 'heavy', 'soft', 'rigid'];

    public bool $hapticsEnabled = true;

    public string $lastPlayed = 'nothing yet';

    public function mount(): void
    {
        $this->hapticsEnabled = Cache::get('showcase.haptics', true);
    }

    public function updatedHapticsEnabled(bool $value): void
    {
        Cache::forever('showcase.haptics', $value);
        Haptics::setEnabled($value);
    }

    /** The button already played its haptic natively; PHP only records which one. */
    public function played(string $haptic): void
    {
        $this->lastPlayed = $haptic.' (native, as the tap landed)';
    }

    public function impact(string $weight): void
    {
        if (in_array($weight, self::IMPACTS, true)) {
            Haptics::impact($weight);
            $this->lastPlayed = $weight.' impact (from PHP)';
        }
    }

    public function sendSuccess(): void
    {
        Feedback::success('Saved')->send();
        $this->lastPlayed = 'success (Feedback)';
    }

    public function sendDanger(): void
    {
        Feedback::danger('Couldn’t save')->send();
        $this->lastPlayed = 'error (Feedback)';
    }

    /** Two outcomes in one interaction: only the strongest, error, plays. */
    public function sendBoth(): void
    {
        Feedback::success('Saved 2 of 3')->send();
        Feedback::danger('1 couldn’t be saved')->send();
        $this->lastPlayed = 'error only (strongest of two Feedback)';
    }

    public function navTitle(): string
    {
        return 'Haptics';
    }

    public function render(): View
    {
        return view('native.haptics-showcase', [
            'groups' => self::GROUPS,
            'impacts' => self::IMPACTS,
        ]);
    }
}
