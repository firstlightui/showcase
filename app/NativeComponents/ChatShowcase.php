<?php

namespace App\NativeComponents;

use FirstlightUI\Media\MediaStorage;
use FirstlightUI\Media\MediaValue;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

/**
 * A group chat that exercises every Chat and Chat Composer event. There is no server: the
 * buttons above the transcript stand in for the other people and for delivery.
 */
class ChatShowcase extends ShowcaseScreen
{
    private const ME = 'me';

    /** @var list<array<string, mixed>> */
    public array $messages = [];

    /** @var list<MediaValue> */
    public array $pending = [];

    public string $draft = '';

    public bool $hasOlder = true;

    public int $olderPages = 0;

    /** @var list<string> */
    public array $typing = [];

    public ?string $lastRead = null;

    public string $lastEvent = 'No events yet';

    public function mount(): void
    {
        $start = Carbon::now()->subDay()->setTime(18, 40);
        $this->messages = [
            $this->incoming('sam', 'Sam', 'Are we still on for dinner tomorrow?', $start),
            $this->incoming('sam', 'Sam', 'I booked the place on George St for 7', $start->copy()->addMinute()),
            $this->incoming('alex', 'Alex', 'I can make it, might be 10 minutes late', $start->copy()->addMinutes(3)),
            $this->outgoing('Perfect, see you both there', $start->copy()->addMinutes(9), 'read'),
            $this->incoming('sam', 'Sam', 'Here is the menu', Carbon::now()->subMinutes(40), [$this->sampleImage('profile.jpg'), $this->sampleImage('locked.jpg')]),
            $this->incoming('alex', 'Alex', 'And the booking confirmation', Carbon::now()->subMinutes(38), [[
                'id' => 'booking',
                'kind' => 'file',
                'name' => 'Booking.pdf',
                'mime' => 'application/pdf',
                'size' => 48_213,
            ]]),
        ];
    }

    public function send(): void
    {
        $body = trim($this->draft);
        if ($body === '' && $this->pending === []) {
            return;
        }

        $this->messages[] = $this->outgoing($body, Carbon::now(), 'sending', array_map(
            fn (MediaValue $media, int $index) => [
                'id' => 'a'.$index,
                'kind' => str_starts_with($media->mime, 'image/') ? 'image' : 'file',
                'source' => $media,
            ],
            $this->pending,
            array_keys($this->pending),
        ));
        $this->draft = '';
        $this->pending = [];
        $this->lastEvent = '@send';
    }

    public function addAttachment(string $tempPath): void
    {
        $extension = pathinfo($tempPath, PATHINFO_EXTENSION);
        $this->pending[] = MediaStorage::commit(
            $tempPath, 'mobile_public', 'showcase-chat', filename: Str::uuid().($extension === '' ? '' : '.'.$extension),
        );
        $this->lastEvent = '@attach';
    }

    public function removeAttachment(int $index): void
    {
        MediaStorage::delete($this->pending[$index] ?? null);
        array_splice($this->pending, $index, 1);
        $this->lastEvent = '@removeAttachment '.$index;
    }

    public function whisperTyping(): void
    {
        $this->lastEvent = '@typing at '.Carbon::now()->format('H:i:s');
    }

    public function loadOlder(): void
    {
        $this->olderPages++;
        $first = Carbon::parse($this->messages[0]['sent-at']);
        $older = [];
        for ($i = 10; $i >= 1; $i--) {
            $older[] = $this->incoming(
                $i % 2 === 0 ? 'sam' : 'alex',
                $i % 2 === 0 ? 'Sam' : 'Alex',
                "Older message {$this->olderPages}.{$i}",
                $first->copy()->subMinutes($i * 7),
            );
        }
        $this->messages = [...$older, ...$this->messages];
        $this->hasOlder = $this->olderPages < 3;
        $this->lastEvent = '@loadOlder page '.$this->olderPages;
    }

    public function markReadUpTo(string $id): void
    {
        $this->lastRead = $id;
        $this->lastEvent = '@read '.$id;
    }

    public function resend(string $id): void
    {
        $this->setStatus($id, 'sending');
        $this->lastEvent = '@retry '.$id;
    }

    public function messageAction(string $key, string $id): void
    {
        if ($key === 'delete') {
            $this->messages = array_values(array_filter($this->messages, fn (array $message) => $message['id'] !== $id));
        }
        $this->lastEvent = "@action {$key} {$id}";
    }

    /** Stands in for a download: writes a small PDF and republishes the attachment with it. */
    public function downloadAttachment(string $messageId, string $attachmentId): void
    {
        $path = 'showcase-chat/'.$attachmentId.'.pdf';
        Storage::disk('mobile_public')->put($path, "%PDF-1.4\n1 0 obj<</Type/Catalog/Pages 2 0 R>>endobj 2 0 obj<</Type/Pages/Count 0/Kids[]>>endobj\ntrailer<</Root 1 0 R>>\n%%EOF\n");
        foreach ($this->messages as &$message) {
            if ($message['id'] !== $messageId) {
                continue;
            }
            foreach ($message['attachments'] as &$attachment) {
                if ($attachment['id'] === $attachmentId) {
                    $attachment['source'] = new MediaValue('mobile_public', $path, 'application/pdf', Storage::disk('mobile_public')->size($path));
                }
            }
        }
        $this->lastEvent = "@openAttachment {$messageId} {$attachmentId}";
    }

    public function samReplies(): void
    {
        $this->typing = [];
        $this->messages[] = $this->incoming('sam', 'Sam', Str::of(collect(['Sounds good', 'On my way', 'Can you bring the receipt?', 'Ha, yes'])->random())->toString(), Carbon::now());
    }

    public function toggleTyping(): void
    {
        $this->typing = $this->typing === [] ? ['Sam'] : ($this->typing === ['Sam'] ? ['Sam', 'Alex'] : []);
    }

    public function deliverLast(): void
    {
        $this->setLastOutgoing(['status' => 'delivered', 'read-by' => null]);
    }

    public function readLast(): void
    {
        $readBy = ($this->lastOutgoing()['read-by'] ?? []) ?: [];
        $next = array_values(array_diff(['Sam', 'Alex'], $readBy))[0] ?? null;
        $this->setLastOutgoing(['status' => 'read', 'read-by' => $next === null ? $readBy : [...$readBy, $next]]);
    }

    public function failLast(): void
    {
        $this->setLastOutgoing(['status' => 'failed']);
    }

    public function navTitle(): string
    {
        return 'Firstlight Chat';
    }

    public function render(): View
    {
        return view('native.chat-showcase');
    }

    private function incoming(string $author, string $name, string $body, Carbon $at, array $attachments = []): array
    {
        return [
            'id' => (string) Str::uuid(),
            'author' => $author,
            'author-name' => $name,
            'body' => $body,
            'sent-at' => $at->toIso8601String(),
            'attachments' => $attachments,
        ];
    }

    private function outgoing(string $body, Carbon $at, string $status, array $attachments = []): array
    {
        return [
            'id' => (string) Str::uuid(),
            'author' => self::ME,
            'body' => $body,
            'sent-at' => $at->toIso8601String(),
            'status' => $status,
            'actions' => ['delete'],
            'attachments' => $attachments,
        ];
    }

    /** A bundled photo, published by its absolute path, so the screen needs no storage to open. */
    private function sampleImage(string $name): array
    {
        $source = resource_path('fixtures/media/'.$name);
        [$width, $height] = getimagesize($source) ?: [0, 0];

        return [
            'id' => pathinfo($name, PATHINFO_FILENAME),
            'kind' => 'image',
            'source' => $source,
            'mime' => 'image/jpeg',
            'size' => (int) filesize($source),
            'width' => $width,
            'height' => $height,
        ];
    }

    private function lastOutgoing(): ?array
    {
        foreach (array_reverse($this->messages) as $message) {
            if ($message['author'] === self::ME) {
                return $message;
            }
        }

        return null;
    }

    private function setLastOutgoing(array $changes): void
    {
        $id = $this->lastOutgoing()['id'] ?? null;
        foreach ($this->messages as &$message) {
            if ($message['id'] === $id) {
                $message = array_merge($message, $changes);
            }
        }
    }

    private function setStatus(string $id, string $status): void
    {
        foreach ($this->messages as &$message) {
            if ($message['id'] === $id) {
                $message['status'] = $status;
            }
        }
    }
}
