<native:column class="w-full flex-1 bg-theme-background">
    <native:scroll-view horizontal class="w-full">
        <native:row class="px-4 py-2 gap-2">
            <firstlight:button label="Sam replies" variant="secondary" size="sm" @press="samReplies" />
            <firstlight:button label="Typing" variant="secondary" size="sm" @press="toggleTyping" />
            <firstlight:button label="Deliver" variant="secondary" size="sm" @press="deliverLast" />
            <firstlight:button label="Read" variant="secondary" size="sm" @press="readLast" />
            <firstlight:button label="Fail" variant="secondary" size="sm" @press="failLast" />
        </native:row>
    </native:scroll-view>
    <native:text class="px-4 pb-1 text-xs text-theme-on-surface-variant">On-device checks: send text, photos and files; long-press for Copy and Delete; scroll up to load older history; open both photos and the PDF; fail then retry. Last event: {{ $lastEvent }}</native:text>

    <firstlight:chat
        class="w-full flex-1"
        :messages="$messages"
        me="me"
        :typing="$typing"
        :has-older="$hasOlder"
        :actions="['delete' => ['label' => 'Delete', 'destructive' => true, 'icon' => 'delete']]"
        @loadOlder="loadOlder"
        @read="markReadUpTo"
        @retry="resend"
        @action="messageAction"
        @openAttachment="downloadAttachment"
        label="Dinner with Sam and Alex"
    />

    <native:bottom-bar>
        <firstlight:chat-composer
            native:model.debounce.300ms="draft"
            :attachments="$pending"
            placeholder="Message"
            attach="photos,camera,files"
            @send="send"
            @attach="addAttachment"
            @removeAttachment="removeAttachment"
            @typing="whisperTyping"
            label="Message to Sam and Alex"
        />
    </native:bottom-bar>
</native:column>
