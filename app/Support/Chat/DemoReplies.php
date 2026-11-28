<?php

namespace App\Support\Chat;

use FirstlightUI\ChatProtocol\Http\SendMessage;
use FirstlightUI\ChatServer\Contracts\ChatNotifier;
use FirstlightUI\ChatServer\Facades\Chat as Server;
use FirstlightUI\ChatServer\Models\Message;
use Illuminate\Support\Str;

/**
 * Stands in for push in the Chat recipe: when you send a message, the
 * other people in the conversation read it, and one of them answers.
 * A real app binds a notifier that sends APNs and FCM pushes.
 */
class DemoReplies implements ChatNotifier
{
    /** Off while {@see DemoChat} seeds history. */
    public static bool $quiet = false;

    private const LINES = [
        'Sounds good',
        'Ha, yes',
        'On it',
        'Give me ten minutes',
        'Can we do 7 instead?',
        'Perfect, thanks!',
        'I\'ll bring snacks',
        'Wait, which one?',
    ];

    public function messageSent(Message $message, array $recipients): void
    {
        if (self::$quiet || $message->author_id !== DemoPeople::ME) {
            return;
        }
        $conversation = $message->conversation;
        $others = array_values(array_filter(array_map(fn ($r) => $r->user, $recipients), fn ($u) => isset(DemoPeople::PEOPLE[$u])));
        if ($others === []) {
            return;
        }

        foreach ($others as $person) {
            Server::moveCursors($conversation, $person, $message->seq, $message->seq);
        }

        $replier = $others[$message->seq % count($others)];
        $line = $message->attachments()->exists() ? 'Nice!' : self::LINES[$message->seq % count(self::LINES)];
        Server::send($conversation, $replier, new SendMessage((string) Str::uuid7(), $line, [], new \DateTimeImmutable));
    }
}
