<?php

namespace App\NativeComponents;

use FirstlightUI\Events\FeedbackActionPressed;
use FirstlightUI\Facades\Feedback;
use Illuminate\View\View;
use Native\Mobile\Attributes\On;

/**
 * Gallery vignette: an inbox triaged with List Item swipe actions, the
 * long-press context menu, a Confirmation Dialog and Transient Feedback
 * with Undo. Sample data only; nothing is sent or stored.
 */
class GalleryInboxScreen extends ShowcaseScreen
{
    public string $filter = 'all';

    /** @var array<string, string> */
    public array $filterOptions = [
        'all' => 'All',
        'unread' => 'Unread',
        'flagged' => 'Flagged',
    ];

    /** @var array<int, array{id: int, from: string, initials: string, subject: string, time: string, today: bool, unread: bool, flagged: bool}> */
    public array $messages = [
        1 => ['id' => 1, 'from' => 'Maya Chen', 'initials' => 'MC', 'subject' => 'Board fitting moved to 7:30', 'time' => '8:12 am', 'today' => true, 'unread' => true, 'flagged' => false],
        2 => ['id' => 2, 'from' => 'Bondi Surf Co.', 'initials' => 'BS', 'subject' => 'Your lesson receipt', 'time' => '7:05 am', 'today' => true, 'unread' => true, 'flagged' => true],
        3 => ['id' => 3, 'from' => 'Tom Okafor', 'initials' => 'TO', 'subject' => 'Photos from Saturday’s session', 'time' => '6:40 am', 'today' => true, 'unread' => false, 'flagged' => false],
        4 => ['id' => 4, 'from' => 'Priya Raman', 'initials' => 'PR', 'subject' => 'Carpool for the dawn session?', 'time' => 'Yesterday', 'today' => false, 'unread' => true, 'flagged' => false],
        5 => ['id' => 5, 'from' => 'Lifeguard roster', 'initials' => 'LR', 'subject' => 'Rip warning lifted at North Bondi', 'time' => 'Thursday', 'today' => false, 'unread' => false, 'flagged' => true],
        6 => ['id' => 6, 'from' => 'Firstlight team', 'initials' => 'FL', 'subject' => 'Every row here is one Blade tag', 'time' => 'Wednesday', 'today' => false, 'unread' => false, 'flagged' => false],
    ];

    /** @var array<int, array<string, mixed>> */
    public array $archived = [];

    public ?int $confirmingDelete = null;

    public function open(int $id): void
    {
        $this->update($id, ['unread' => false]);
    }

    public function toggleRead(int $id): void
    {
        $this->update($id, ['unread' => ! ($this->messages[$id]['unread'] ?? false)]);
    }

    public function toggleFlag(int $id): void
    {
        $this->update($id, ['flagged' => ! ($this->messages[$id]['flagged'] ?? false)]);
    }

    public function archive(int $id): void
    {
        if (! isset($this->messages[$id])) {
            return;
        }

        $this->archived[$id] = $this->messages[$id];
        unset($this->messages[$id]);

        Feedback::success('Archived “'.$this->archived[$id]['subject'].'”')
            ->id('inbox-archive-'.$id)
            ->action('Undo', 'undo-archive')
            ->send();
    }

    #[On(FeedbackActionPressed::class)]
    public function undoArchive(string $id, string $actionKey): void
    {
        $messageId = (int) str_replace('inbox-archive-', '', $id);

        if ($actionKey !== 'undo-archive' || ! isset($this->archived[$messageId])) {
            return;
        }

        $this->messages[$messageId] = $this->archived[$messageId];
        unset($this->archived[$messageId]);
        ksort($this->messages);
    }

    public function requestDelete(int $id): void
    {
        $this->confirmingDelete = isset($this->messages[$id]) ? $id : null;
    }

    public function cancelDelete(): void
    {
        $this->confirmingDelete = null;
    }

    public function deleteMessage(): void
    {
        $id = $this->confirmingDelete;
        $this->confirmingDelete = null;

        if ($id === null || ! isset($this->messages[$id])) {
            return;
        }

        unset($this->messages[$id]);
        Feedback::message('Message deleted')->send();
    }

    public function navTitle(): string
    {
        return 'Inbox';
    }

    public function render(): View
    {
        $rows = collect($this->messages)
            ->filter(fn (array $message): bool => match ($this->filter) {
                'unread' => $message['unread'],
                'flagged' => $message['flagged'],
                default => true,
            })
            ->map($this->row(...));

        return view('native.gallery-inbox', [
            'sections' => array_filter([
                'Today' => $rows->where('today', true)->all(),
                'Earlier' => $rows->where('today', false)->all(),
            ]),
            'unread' => collect($this->messages)->where('unread', true)->count(),
            'flagged' => collect($this->messages)->where('flagged', true)->count(),
            'emptyMessage' => match ($this->filter) {
                'unread' => 'No unread messages.',
                'flagged' => 'Nothing is flagged.',
                default => 'Inbox zero. Undo brings an archived message back.',
            },
            'pendingSubject' => $this->messages[$this->confirmingDelete ?? 0]['subject'] ?? '',
        ]);
    }

    /**
     * One List Item's props. Unread and flagged rows show a decorative
     * status icon (spoken through the row's label); others show the time.
     *
     * @param  array{id: int, from: string, initials: string, subject: string, time: string, today: bool, unread: bool, flagged: bool}  $message
     * @return array<string, mixed>
     */
    private function row(array $message): array
    {
        $id = $message['id'];
        $read = $message['unread']
            ? ['label' => 'Mark as read', 'icon' => 'drafts', 'ios' => 'envelope.open']
            : ['label' => 'Mark as unread', 'icon' => 'mark_email_unread', 'ios' => 'envelope.badge'];

        return [
            ...$message,
            'icon' => match (true) {
                $message['unread'] => ['circle', 'circle.fill', 'circle', 'info'],
                $message['flagged'] => ['flag', 'flag.fill', 'flag', 'warning'],
                default => null,
            },
            'a11yLabel' => implode(', ', array_filter([
                $message['from'],
                $message['subject'],
                $message['time'],
                $message['unread'] ? 'unread' : null,
                $message['flagged'] ? 'flagged' : null,
            ])),
            'leadingActions' => [
                ['method' => "toggleRead({$id})", 'label' => $message['unread'] ? 'Read' : 'Unread', 'icon' => $read['icon'], 'ios' => $read['ios']],
            ],
            'trailingActions' => [
                ['method' => "requestDelete({$id})", 'label' => 'Delete', 'icon' => 'delete', 'ios' => 'trash', 'role' => 'destructive'],
                ['method' => "archive({$id})", 'label' => 'Archive', 'icon' => 'archive', 'ios' => 'archivebox'],
            ],
            'contextMenu' => [
                ['method' => "toggleRead({$id})", ...$read],
                ['method' => "toggleFlag({$id})", 'label' => $message['flagged'] ? 'Unflag' : 'Flag', 'icon' => 'flag', 'ios' => $message['flagged'] ? 'flag.slash' : 'flag'],
                ['method' => "archive({$id})", 'label' => 'Archive', 'icon' => 'archive', 'ios' => 'archivebox'],
                ['method' => "requestDelete({$id})", 'label' => 'Delete', 'icon' => 'delete', 'ios' => 'trash', 'role' => 'destructive'],
            ],
        ];
    }

    /** @param array<string, bool> $changes */
    private function update(int $id, array $changes): void
    {
        if (isset($this->messages[$id])) {
            $this->messages[$id] = [...$this->messages[$id], ...$changes];
        }
    }
}
