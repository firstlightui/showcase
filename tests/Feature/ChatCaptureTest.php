<?php

use Illuminate\Support\Facades\Storage;
use Native\Mobile\Testing\Native;

function chatCaptureNode(array $tree, string $type): ?array
{
    if (($tree['type'] ?? null) === $type) {
        return $tree;
    }
    foreach ($tree['children'] ?? [] as $child) {
        if (($found = chatCaptureNode($child, $type)) !== null) {
            return $found;
        }
    }

    return null;
}

it('publishes the stable Chat documentation capture fixture', function () {
    $screen = Native::visit('/captures/chat')->assertAccessible();
    $props = chatCaptureNode($screen->tree(), 'firstlight.chat')['props'];
    $messages = json_decode($props['messages_json'], true);

    expect(array_column($messages, 'id'))->toBe(['m2', 'm4', 'm5', 'm6'])
        ->and($props['typing_text'])->toBe('Sam is typing')
        ->and(array_column($messages, 'status_line'))->toBe(['', '', '', 'Read by Sam and Alex'])
        ->and($messages[2]['status'])->toBe('failed')
        ->and($messages[0]['attachments'][0]['path'])->toEndWith('fixtures/media/park.jpg');
});

it('publishes the stable Chat Composer documentation capture fixture', function () {
    Storage::fake('mobile_public');
    $screen = Native::visit('/captures/chat-composer')->assertAccessible();
    $props = chatCaptureNode($screen->tree(), 'firstlight.chat-composer')['props'];

    expect($props['value'])->toBe('Here are the photo and the booking')
        ->and(array_column(json_decode($props['attachments_json'], true), 'kind'))->toBe(['image', 'file'])
        ->and($props['can_send'])->toBeTrue();
});
