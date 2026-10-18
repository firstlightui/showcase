<native:scroll-view class="w-full flex-1 bg-theme-background">
    <native:column class="w-full p-4 gap-5 pb-32">
        <native:text class="text-sm text-theme-on-surface">On-device checks: submit empty; all three fields show real Laravel errors and accepted submissions stays zero. Correct one field at a time, agree and Continue; errors clear and one success feedback appears. Submit invalid data again; no stale success should remain. Reset clears the form and count. No account is created.</native:text>
        <native:column class="w-full gap-2">
            <native:text class="text-sm font-semibold text-theme-on-surface">Laravel rules</native:text>
            <native:text class="text-sm text-theme-on-surface-variant">
                Submit empty to fail, then enter a valid name, email, and agreement to pass. Email also validates on blur.
            </native:text>
        </native:column>

        <native:column class="w-full gap-3">
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
        </native:column>

        @if ($saved)
            <firstlight:callout tone="success" :message="$saved" />
        @endif

        <native:text class="text-sm text-theme-on-surface">PHP accepted state: name {{ $name ?: '(empty)' }}; email {{ $email ?: '(empty)' }}; agreed {{ $accepted ? 'true' : 'false' }}; accepted submissions {{ $saveCount }}</native:text>
        <native:row class="gap-3 pb-6">
            <firstlight:button ref="save" label="Continue" @press="save" />
            <firstlight:button ref="resetForm" label="Reset" variant="secondary" @press="resetForm" />
        </native:row>
    </native:column>
</native:scroll-view>
