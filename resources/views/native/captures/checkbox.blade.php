<native:scroll-view class="w-full flex-1 bg-theme-background">
    <native:column class="w-full p-5 gap-5">
        <firstlight:checkbox
            native:model="acceptedTerms"
            label="I agree to the terms"
            helper="Required before continuing."
            required
            a11y-hint="Required before creating your account"
        />

        <firstlight:checkbox
            native:model="diagnostics"
            label="Share anonymous diagnostics"
            helper="Help improve application reliability."
        />

        <firstlight:checkbox
            :value="$disabledAgreement"
            label="Disabled agreement"
            disabled
        />

        <firstlight:checkbox
            native:model="agreementWithError"
            label="Agreement with error"
            error="Agreement is required before continuing."
            required
            a11y-hint="Resolve the agreement error"
        />
    </native:column>
</native:scroll-view>
