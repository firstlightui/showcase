<?php

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Native\Mobile\Testing\Native;
use Native\Mobile\Testing\TestableComponent;

beforeEach(fn () => Storage::fake('mobile_public'));

function chatShowcaseNode(array $tree, string $type): ?array
{
    if (($tree['type'] ?? null) === $type) {
        return $tree;
    }
    foreach ($tree['children'] ?? [] as $child) {
        if (($found = chatShowcaseNode($child, $type)) !== null) {
            return $found;
        }
    }

    return null;
}

function chatShowcaseMessages(TestableComponent $screen): array
{
    return json_decode(chatShowcaseNode($screen->tree(), 'firstlight.chat')['props']['messages_json'], true);
}

it('sends a draft with an attachment, then shows delivery and group read receipts', function () {
    $photo = UploadedFile::fake()->image('menu.jpg', 40, 30);
    $screen = Native::visit('/components/chat');

    $screen->input('draft', 'See you at 7')
        ->fireEvent('addAttachment', TestableComponent::EVENT_TEXT_CHANGE, ['text' => $photo->getPathname()]);
    expect(json_decode(chatShowcaseNode($screen->tree(), 'firstlight.chat-composer')['props']['attachments_json'], true))->toHaveCount(1);

    $screen->press('send');
    $sent = last(chatShowcaseMessages($screen));
    expect($sent['body'])->toBe('See you at 7')
        ->and($sent['attachments'][0]['kind'])->toBe('image')
        ->and($sent['status_line'])->toBe('Sending')
        ->and(chatShowcaseNode($screen->tree(), 'firstlight.chat-composer')['props']['value'])->toBe('');

    $screen->tap('Deliver');
    expect(last(chatShowcaseMessages($screen))['status_line'])->toBe('Delivered');
    $screen->tap('Read');
    expect(last(chatShowcaseMessages($screen))['status_line'])->toBe('Read by Sam');
    $screen->tap('Read');
    expect(last(chatShowcaseMessages($screen))['status_line'])->toBe('Read by Sam and Alex');
});

it('loads older history, reports reads and opens a file after downloading it', function () {
    $screen = Native::visit('/components/chat');
    $messages = chatShowcaseMessages($screen);
    $booking = last($messages);

    $screen->press('loadOlder');
    expect(chatShowcaseMessages($screen))->toHaveCount(count($messages) + 10);

    $screen->fireEvent('markReadUpTo', TestableComponent::EVENT_TEXT_CHANGE, ['text' => $booking['id']])
        ->assertSet('lastRead', $booking['id']);

    expect($booking['attachments'][0]['path'])->toBe('')
        ->and($booking['attachments'][0]['on_open'])->toBeGreaterThan(0);
    $screen->fireEvent('downloadAttachment', TestableComponent::EVENT_TEXT_CHANGE, ['text' => 'booking']);
    $opened = last(chatShowcaseMessages($screen))['attachments'][0];
    expect($opened['path'])->not->toBe('')
        ->and($opened['mime'])->toBe('application/pdf');
});

it('retries a failed message and deletes through its menu action', function () {
    $screen = Native::visit('/components/chat')->input('draft', 'Running late')->press('send')->tap('Fail');
    $failed = last(chatShowcaseMessages($screen));
    expect($failed['status'])->toBe('failed');

    $screen->fireEvent('resend', TestableComponent::EVENT_TEXT_CHANGE, ['text' => $failed['id']]);
    expect(last(chatShowcaseMessages($screen))['status'])->toBe('sending');

    $screen->fireEvent('messageAction("delete")', TestableComponent::EVENT_TEXT_CHANGE, ['text' => $failed['id']]);
    expect(collect(chatShowcaseMessages($screen))->pluck('id'))->not->toContain($failed['id']);
});

it('publishes only the requested window of a long chat', function () {
    $screen = Native::visit('/components/chat-long');
    $props = chatShowcaseNode($screen->tree(), 'firstlight.chat')['props'];
    expect([$props['windowed'], $props['count'], $props['window_from'], $props['window_to']])->toBe([true, 5000, 4880, 4999])
        ->and(chatShowcaseMessages($screen))->toHaveCount(120);

    $screen->fireEvent('setWindow', TestableComponent::EVENT_TEXT_CHANGE, ['text' => '100,219']);
    $messages = chatShowcaseMessages($screen);
    expect([$messages[0]['id'], last($messages)['id']])->toBe(['m100', 'm219']);

    $screen->input('draft', 'Still there?')->press('send');
    $props = chatShowcaseNode($screen->tree(), 'firstlight.chat')['props'];
    expect([$props['count'], $props['window_to']])->toBe([5001, 5000])
        ->and(last(chatShowcaseMessages($screen))['body'])->toBe('Still there?');
});
