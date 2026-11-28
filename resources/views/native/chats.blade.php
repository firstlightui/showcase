@if ($chatConversations === [])
    <native:column class="w-full flex-1 bg-theme-background items-center justify-center p-6">
        <native:text class="text-theme-on-surface-variant">Loading conversations…</native:text>
    </native:column>
@else
    <firstlight:list class="w-full flex-1 bg-theme-background">
        <firstlight:list-section footer="Messages, receipts and unread counts come from the Chat plugin. Its server runs inside Showcase, so the people here answer as soon as you send.">
            @foreach ($chatConversations as $conversation)
                @php($monogram = collect(explode(' ', $conversation['title']))->take(2)->map(fn ($w) => mb_strtoupper(mb_substr($w, 0, 1)))->join(''))
                @if ($conversation['unread'] > 0)
                    <firstlight:list-item
                        :headline="$conversation['title']"
                        :supporting="$conversation['preview'] ?? 'No messages yet'"
                        :leading-monogram="$monogram"
                        :trailing-status="(string) $conversation['unread']"
                        trailing-status-tone="info"
                        navigates
                        a11y-hint="Opens the conversation"
                        @press="open('{{ $conversation['id'] }}')"
                    />
                @else
                    <firstlight:list-item
                        :headline="$conversation['title']"
                        :supporting="$conversation['preview'] ?? 'No messages yet'"
                        :leading-monogram="$monogram"
                        navigates
                        a11y-hint="Opens the conversation"
                        @press="open('{{ $conversation['id'] }}')"
                    />
                @endif
            @endforeach
        </firstlight:list-section>
    </firstlight:list>
@endif
