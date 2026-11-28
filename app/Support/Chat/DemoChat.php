<?php

namespace App\Support\Chat;

use FirstlightUI\ChatProtocol\Http\SendMessage;
use FirstlightUI\ChatServer\Facades\Chat as Server;
use FirstlightUI\ChatServer\Models\Conversation;
use FirstlightUI\ChatServer\Models\Message;
use Illuminate\Support\Str;

/** The Chat recipe's starting conversations, created on the in-app server the first time. */
class DemoChat
{
    public static function seed(): void
    {
        if (Conversation::query()->exists()) {
            return;
        }

        DemoReplies::$quiet = true;
        try {
            self::conversations();
        } finally {
            DemoReplies::$quiet = false;
        }
    }

    private static function conversations(): void
    {
        $sam = Server::direct(DemoPeople::ME, 'sam');
        self::history($sam, [
            [-2880, 'sam', 'Are you around this weekend?'],
            [-2875, 'me', 'Saturday yes, Sunday I\'m out'],
            [-2870, 'sam', 'Saturday it is'],
            [-30, 'sam', 'Still on for tomorrow?'],
            [-28, 'me', 'Yes! 10am at the trailhead'],
            [-5, 'sam', 'Great, see you there'],
        ], read: ['me' => 5, 'sam' => 5]);

        $hike = Server::group('Weekend hike', [DemoPeople::ME, 'priya', 'jo']);
        self::history($hike, [
            [-120, 'priya', 'Forecast says sunny on Saturday'],
            [-118, 'jo', 'Finally'],
            [-60, 'me', 'Who\'s driving?'],
            [-12, 'priya', 'I can take three'],
            [-11, 'jo', 'I\'ll bring the map'],
        ], read: ['me' => 3, 'priya' => 5, 'jo' => 4]);
    }

    /** @param  list<array{int, string, string}>  $messages  minutes from now, author, body */
    private static function history(Conversation $conversation, array $messages, array $read): void
    {
        foreach ($messages as [$minutes, $author, $body]) {
            $at = now()->addMinutes($minutes)->toDateTimeImmutable();
            [$message] = Server::send($conversation, $author, new SendMessage((string) Str::uuid7(), $body, [], $at));
            // Seeded history happened in the past: store it at the time it was sent.
            Message::whereKey($message->id)->update(['created_at' => $at]);
        }
        foreach ($read as $person => $seq) {
            Server::moveCursors($conversation, $person, $seq, $seq);
        }
    }
}
