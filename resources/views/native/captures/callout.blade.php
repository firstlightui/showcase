<native:scroll-view class="w-full flex-1 bg-theme-background">
    <native:column class="w-full p-5 gap-4">
        <firstlight:callout message="This is a neutral notice." tone="neutral" />
        <firstlight:callout message="Appointments sync every five minutes." />
        <firstlight:callout message="The referral was sent successfully." tone="success" />
        <firstlight:callout
            message="Your changes have not been submitted."
            tone="warning"
            action-label="Review changes"
            a11y-label="Submission warning: changes have not been submitted"
            @press="reviewChanges"
        />
        <firstlight:callout message="The upload could not be completed." tone="danger" />
    </native:column>
</native:scroll-view>
