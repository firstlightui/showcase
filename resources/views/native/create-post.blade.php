<native:scroll-view class="w-full flex-1">
    <native:column class="w-full p-4 gap-4">
        <firstlight:callout error-for="form" />

        <firstlight:text-field
            label="Title"
            autocapitalize="sentences"
            submit-label="next"
            native:model.blur="title"
            required
        />

        <firstlight:text-area
            label="Body"
            native:model.blur="body"
            required
        />

        <firstlight:button variant="primary" :loading="$submitting" @press="save">
            Save post
        </firstlight:button>
    </native:column>
</native:scroll-view>
