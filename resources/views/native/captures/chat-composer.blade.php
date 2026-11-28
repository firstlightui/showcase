<native:column class="w-full flex-1 bg-theme-background">
    <native:spacer class="flex-1" />

    <native:bottom-bar>
        <firstlight:chat-composer
            value="Here are the photo and the booking"
            :attachments="$pending"
            placeholder="Message"
            attach="photos,camera,files"
            @send="noop"
            @attach="noop"
            @removeAttachment="remove"
            label="Message to Sam"
        />
    </native:bottom-bar>
</native:column>
