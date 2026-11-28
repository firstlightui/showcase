<?php

namespace App\NativeComponents;

use Illuminate\Support\Carbon;
use Illuminate\View\View;

/**
 * Windowed Chat: 5,000 generated messages, of which PHP publishes only the slice native asks
 * for with @windowChange. Scroll to the top and back; nothing on screen should jump.
 */
class ChatLongShowcase extends ShowcaseScreen
{
    private const COUNT = 5000;

    private const WINDOW = 120;

    public int $from = self::COUNT - self::WINDOW;

    public int $to = self::COUNT - 1;

    public int $windowChanges = 0;

    public string $draft = '';

    /** Messages sent from this screen, after the generated ones. */
    public array $sent = [];

    public function setWindow(int $from, int $to): void
    {
        $this->from = max(0, $from);
        $this->to = min($this->count() - 1, $to);
        $this->windowChanges++;
    }

    public function send(): void
    {
        if (trim($this->draft) === '') {
            return;
        }
        $this->sent[] = trim($this->draft);
        $this->draft = '';
        // Follow the newest end so the outgoing message is published.
        $this->to = $this->count() - 1;
        $this->from = max(0, $this->to - self::WINDOW + 1);
    }

    public function navTitle(): string
    {
        return 'Long Chat';
    }

    public function render(): View
    {
        return view('native.chat-long-showcase', [
            'count' => $this->count(),
            'window' => array_map($this->message(...), range($this->from, $this->to)),
        ]);
    }

    private function count(): int
    {
        return self::COUNT + count($this->sent);
    }

    /** Message $index is generated from its index, so any window can be rebuilt on demand. */
    private function message(int $index): array
    {
        $start = Carbon::parse('2026-01-01 08:00', config('app.timezone'));

        if ($index >= self::COUNT) {
            return [
                'id' => 'sent-'.($index - self::COUNT),
                'author' => 'me',
                'body' => $this->sent[$index - self::COUNT],
                'sent-at' => $start->copy()->addMinutes(self::COUNT * 90 + $index - self::COUNT)->toIso8601String(),
                'status' => 'sent',
            ];
        }

        $mine = $index % 3 === 0;
        // Every eighth message is long, so rows have different heights.
        $body = "Message {$index}".($index % 8 === 0 ? ': '.str_repeat('a longer line that wraps across the bubble, ', 3) : '');

        return [
            'id' => 'm'.$index,
            'author' => $mine ? 'me' : 'sam',
            'author-name' => $mine ? null : 'Sam',
            'body' => $body,
            'sent-at' => $start->copy()->addMinutes($index * 90)->toIso8601String(),
            'status' => $mine ? 'read' : null,
        ];
    }
}
