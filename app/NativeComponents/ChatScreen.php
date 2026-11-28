<?php

namespace App\NativeComponents;

use FirstlightUI\Chat\Concerns\ShowsChat;
use FirstlightUI\NativeComponent;
use Illuminate\View\View;

/** One conversation in the Chat recipe: the plugin's ShowsChat feeding Chat and Chat Composer. */
class ChatScreen extends NativeComponent
{
    use ShowsChat;

    public function mount(string $conversation): void
    {
        $this->openChat($conversation);
    }

    public function navTitle(): string
    {
        return $this->chatTitle !== '' ? $this->chatTitle : 'Chat';
    }

    public function render(): View
    {
        return view('native.chat');
    }
}
