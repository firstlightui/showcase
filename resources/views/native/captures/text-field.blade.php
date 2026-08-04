<native:scroll-view class="w-full flex-1 bg-theme-background">
    <native:column class="w-full p-5 gap-6">
        <firstlight:text-field
            native:model="email"
            label="Email"
            helper="Used for appointment updates."
            keyboard="email"
            content-type="email"
            autocapitalize="none"
            :autocorrect="false"
            leading-icon="mail"
            leading-icon-ios="envelope"
            leading-icon-android="email"
            clearable
        />

        <firstlight:text-field
            native:model.blur="password"
            label="Password"
            helper="Use at least twelve characters."
            content-type="password"
            autocapitalize="none"
            :autocorrect="false"
            secure
            revealable
        />

        <firstlight:text-field
            value="invalid-address"
            label="Email with error"
            error="Enter a valid email address."
            keyboard="email"
            content-type="email"
        />
    </native:column>
</native:scroll-view>
