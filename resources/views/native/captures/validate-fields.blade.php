<native:scroll-view class="w-full flex-1 bg-theme-background">
    <native:column class="w-full p-5 gap-5">
        <firstlight:text-field
            ref="name"
            label="Name"
            placeholder="Alex Rivera"
            native:model.blur="name"
        />
        <firstlight:text-field
            ref="email"
            label="Email"
            placeholder="you@example.com"
            keyboard="email"
            content-type="email"
            autocapitalize="none"
            :autocorrect="false"
            native:model.blur="email"
        />
        <firstlight:checkbox
            ref="accepted"
            label="I agree to the terms"
            native:model="accepted"
        />
        <firstlight:button ref="save" label="Continue" @press="save" />
    </native:column>
</native:scroll-view>
