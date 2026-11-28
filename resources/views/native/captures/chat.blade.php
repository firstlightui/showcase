<native:column class="w-full flex-1 bg-theme-background">
    <firstlight:chat
        class="w-full flex-1"
        :messages="$messages"
        me="me"
        :typing="['Sam']"
        @retry="resend"
        label="Dinner with Sam and Alex"
    />

    <native:bottom-bar>
        <firstlight:chat-composer value="See you soon" placeholder="Message" attach="photos,camera,files" @send="noop" @attach="noop" label="Message to Sam and Alex" />
    </native:bottom-bar>
</native:column>
