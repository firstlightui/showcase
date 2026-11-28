<native:column class="w-full flex-1 bg-theme-background">
    <native:text class="px-4 py-2 text-xs text-theme-on-surface-variant">On-device checks: scroll to the top and back without jumps. {{ number_format($count) }} messages · window {{ $from }}–{{ $to }} · {{ $windowChanges }} window changes</native:text>

    <firstlight:chat
        class="w-full flex-1"
        :messages="$window"
        me="me"
        :count="$count"
        :window-from="$from"
        :window-to="$to"
        @windowChange="setWindow"
        label="Long chat with Sam"
    />

    <native:bottom-bar>
        <firstlight:chat-composer
            native:model.debounce.300ms="draft"
            placeholder="Message"
            @send="send"
            label="Message to Sam"
        />
    </native:bottom-bar>
</native:column>
