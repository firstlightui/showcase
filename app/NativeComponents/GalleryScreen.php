<?php

namespace App\NativeComponents;

use Carbon\CarbonImmutable;
use FirstlightUI\Events\FeedbackActionPressed;
use FirstlightUI\Facades\Feedback;
use Illuminate\View\View;
use Native\Mobile\Attributes\On;

/**
 * Gallery vignette: a surf-lesson booking built only from Firstlight
 * controls. Every value on screen is PHP state; booking sends Transient
 * Feedback whose Undo restores the boards. Sample data only.
 */
class GalleryScreen extends ShowcaseScreen
{
    public const TIMEZONE = 'Australia/Sydney';

    public const CAPACITY = 12;

    public const LESSON_PRICE = 45;

    public const HIRE_PRICE = 15;

    /** Boards already taken by other riders, per session. */
    public const TAKEN = ['06:00' => 9, '07:30' => 4, '09:00' => 12];

    public ?string $date = null;

    public string $session = '06:00';

    public int $riders = 2;

    public bool $hire = true;

    /** @var array<string, array{id: string, slot: string, riders: int}> */
    public array $bookings = [];

    public function mount(): void
    {
        $this->date = $this->today()->next(CarbonImmutable::SATURDAY)->toDateString();
    }

    public function book(): void
    {
        if ($this->date === null || $this->riders > $this->boardsLeft()) {
            return;
        }

        $id = 'booking-'.(count($this->bookings) + 1).'-'.$this->slot();
        $this->bookings[$id] = ['id' => $id, 'slot' => $this->slot(), 'riders' => $this->riders];

        Feedback::success('Booked '.$this->sessionLabel().' for '.$this->ridersLabel($this->riders))
            ->id($id)
            ->action('Undo', 'undo-booking')
            ->send();

        // Keep the next booking valid: never ask for more boards than remain.
        $this->riders = max(1, min($this->riders, $this->boardsLeft()));
    }

    #[On(FeedbackActionPressed::class)]
    public function undoBooking(string $id, string $actionKey): void
    {
        if ($actionKey === 'undo-booking' && isset($this->bookings[$id])) {
            unset($this->bookings[$id]);
        }
    }

    public function openInbox(): void
    {
        $this->navigate('/gallery/inbox');
    }

    public function showsBackButton(): bool
    {
        return false;
    }

    public function navTitle(): string
    {
        return 'Gallery';
    }

    public function boardsLeft(?string $session = null): int
    {
        $session ??= $this->session;
        $slot = $this->date.'|'.$session;
        $mine = collect($this->bookings)->where('slot', $slot)->sum('riders');

        return max(0, self::CAPACITY - self::TAKEN[$session] - $mine);
    }

    public function total(): int
    {
        return $this->riders * (self::LESSON_PRICE + ($this->hire ? self::HIRE_PRICE : 0));
    }

    public function render(): View
    {
        $boardsLeft = $this->boardsLeft();
        $booked = collect($this->bookings)->where('slot', $this->slot())->sum('riders');

        return view('native.gallery', [
            'today' => $this->today()->toDateString(),
            'lastDay' => $this->today()->addDays(27)->toDateString(),
            'sessionOptions' => collect(self::TAKEN)->keys()->map(fn (string $session): array => [
                'value' => $session,
                'label' => $this->clock($session),
                'disabled' => $this->boardsLeft($session) === 0 && $session !== $this->session,
            ])->all(),
            'boardsLeft' => $boardsLeft,
            'booked' => $booked,
            'canBook' => $this->date !== null && $boardsLeft > 0 && $this->riders <= $boardsLeft,
            'sessionLabel' => $this->sessionLabel(),
            'ridersLabel' => $this->ridersLabel($this->riders),
            'total' => '$'.$this->total(),
        ]);
    }

    private function slot(): string
    {
        return $this->date.'|'.$this->session;
    }

    private function today(): CarbonImmutable
    {
        return CarbonImmutable::now(self::TIMEZONE)->startOfDay();
    }

    private function clock(string $session): string
    {
        return CarbonImmutable::createFromFormat('H:i', $session, self::TIMEZONE)->format('g:i');
    }

    private function sessionLabel(): string
    {
        $day = $this->date === null
            ? 'no day'
            : CarbonImmutable::createFromFormat('Y-m-d', $this->date, self::TIMEZONE)->format('D j M');

        return $day.', '.$this->clock($this->session).' am';
    }

    private function ridersLabel(int $riders): string
    {
        return $riders === 1 ? '1 rider' : $riders.' riders';
    }
}
