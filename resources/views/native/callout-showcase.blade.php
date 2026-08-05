<native:scroll-view class="w-full flex-1 bg-theme-background">
    <native:column class="w-full p-4 gap-5">
        <native:column class="w-full gap-3">
            <native:text class="text-sm font-semibold text-theme-on-surface">Semantic tones</native:text>
            <firstlight:callout message="This is a neutral notice." tone="neutral" />
            <firstlight:callout message="Appointments sync every five minutes." />
            <firstlight:callout message="The referral was sent successfully." tone="success" />
            <firstlight:callout message="Your changes have not been submitted." tone="warning" />
            <firstlight:callout message="The upload could not be completed." tone="danger" />
        </native:column>

        <native:column class="w-full gap-3">
            <native:text class="text-sm font-semibold text-theme-on-surface">Optional action</native:text>
            <firstlight:callout
                ref="actionCallout"
                message="Your changes have not been submitted."
                tone="warning"
                action-label="Review changes"
                a11y-label="Submission warning: changes have not been submitted"
                a11y-hint="Review the form before continuing"
                @press="reviewChanges"
            />
            <native:text class="text-sm text-theme-on-surface-variant">{{ $lastAction }} · {{ $actionCount }} presses</native:text>
        </native:column>

        <native:column class="w-full gap-3 pb-6">
            <native:text class="text-sm font-semibold text-theme-on-surface">Long copy and text scaling</native:text>
            <firstlight:callout
                message="The multidisciplinary care plan is awaiting review from every assigned clinician before it can be shared with the patient."
                tone="info"
            />
        </native:column>
    </native:column>
</native:scroll-view>
