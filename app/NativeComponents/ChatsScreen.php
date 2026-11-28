<?php

namespace App\NativeComponents;

use App\Support\Chat\DemoChat;
use FirstlightUI\Chat\Concerns\ListsConversations;
use FirstlightUI\NativeComponent;
use Illuminate\View\View;

/**
 * The Chat recipe's conversation list (firstlightui/chat). Everything comes
 * from the plugin's cache on the phone; the server runs inside Showcase.
 */
class ChatsScreen extends NativeComponent
{
    use ListsConversations;

    public function mount(): void
    {
        DemoChat::seed();
        $this->listConversations();
    }

    public function navTitle(): string
    {
        return 'Chats';
    }

    public function open(string $conversation): void
    {
        $this->navigate('/chats/'.$conversation);
    }

    public function render(): View
    {
        return view('native.chats');
    }
}
