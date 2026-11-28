<?php

use App\NativeComponents\ChatScreen;
use FirstlightUI\ChatServer\Models\Conversation;
use FirstlightUI\ChatServer\Models\Member;
use FirstlightUI\ChatServer\Models\Message;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Native\Mobile\AsyncTask;
use Native\Mobile\Testing\Native;

uses(RefreshDatabase::class);

beforeEach(fn () => AsyncTask::fake());

it('lists the seeded conversations with their unread counts', function () {
    $screen = Native::visit('/chats')->assertAccessible()->assertNavTitle('Chats');
    $screen->call('refreshConversations');

    $list = collect($screen->get('chatConversations'))->keyBy('title');
    expect($list->keys()->sort()->values()->all())->toBe(['Sam Lee', 'Weekend hike'])
        ->and($list['Sam Lee']['unread'])->toBe(1)
        ->and($list['Weekend hike']['unread'])->toBe(2)
        ->and($list['Weekend hike']['preview'])->toBe('I\'ll bring the map');
    $screen->assertSee('Weekend hike');
});

it('sends a message, gets an answer and shows it read', function () {
    Native::visit('/chats');
    $hike = Conversation::where('title', 'Weekend hike')->sole();

    $chat = Native::visit('/chats/'.$hike->id)->assertAccessible()->assertNavTitle('Weekend hike');
    $chat->call('refreshChat');
    expect(array_column($chat->get('chatMessages'), 'body'))->toContain('Forecast says sunny on Saturday');

    $chat->set('chatDraft', 'Count me in')->call('chatSend')->call('refreshChat');

    $messages = $chat->get('chatMessages');
    $mine = collect($messages)->firstWhere('body', 'Count me in');
    expect(Message::where('body', 'Count me in')->where('author_id', 'me')->exists())->toBeTrue()
        ->and(end($messages)['author'])->not->toBe('me')
        ->and($mine['status'])->toBe('read')
        ->and($mine['read-by'])->toBe(['Priya Shah', 'Jo Park']);
});

it('marks the conversation read on the server when the newest message is seen', function () {
    Native::visit('/chats');
    $sam = Conversation::where('kind', 'direct')->sole();
    $chat = Native::test(ChatScreen::class, ['conversation' => $sam->id]);
    $chat->call('refreshChat');

    $last = collect($chat->get('chatMessages'))->last();
    $chat->call('chatRead', $last['id']);

    expect(Member::where('conversation_id', $sam->id)->where('user_id', 'me')->value('read_seq'))->toBe(6);

    // Back on the list, the conversation has nothing unread and still renders.
    $list = Native::visit('/chats');
    $list->call('refreshConversations');
    expect(collect($list->get('chatConversations'))->firstWhere('title', 'Sam Lee')['unread'])->toBe(0);
    $list->assertSee('Sam Lee');
});
