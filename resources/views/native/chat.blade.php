<native:column class="w-full flex-1 bg-theme-background">
    <firstlight:chat
        class="w-full flex-1"
        :messages="$chatMessages"
        me="{{ $chatMe }}"
        :typing="$chatTyping"
        :has-older="$chatHasOlder"
        @read="chatRead"
        @retry="chatRetry"
        @loadOlder="chatLoadOlder"
        @openAttachment="chatOpenAttachment"
        label="Chat with {{ $chatTitle }}"
    />

    <native:bottom-bar>
        <firstlight:chat-composer
            native:model.debounce.300ms="chatDraft"
            :attachments="$chatPending"
            placeholder="Message"
            attach="photos,camera,files"
            @send="chatSend"
            @attach="chatAttach"
            @removeAttachment="chatRemoveAttachment"
            @typing="chatTyped"
            label="Message to {{ $chatTitle }}"
        />
    </native:bottom-bar>
</native:column>
