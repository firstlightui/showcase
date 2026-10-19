<native:scroll-view class="w-full flex-1 bg-theme-background">
    <native:column class="w-full p-5 gap-6">
        <firstlight:code-editor
            native:model="payload"
            language="json"
            label="Webhook payload"
            helper="Sent as the request body."
            :required="true"
            :min-lines="5"
            :max-lines="8"
        />

        <firstlight:code-editor
            native:model="environment"
            language="env"
            label="Environment"
            error="APP_KEY is missing."
            :min-lines="4"
            :max-lines="6"
        />

        <firstlight:code-editor
            :value="$workflow"
            language="yaml"
            label="Deploy workflow"
            helper="Selection and copy remain native."
            :read-only="true"
            :min-lines="4"
            :max-lines="6"
        />
    </native:column>
</native:scroll-view>
