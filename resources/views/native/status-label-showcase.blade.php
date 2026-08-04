<native:scroll-view class="w-full flex-1 bg-theme-background">
    <native:column class="w-full p-4 gap-5">
        <native:column class="w-full gap-3">
            <native:text class="text-sm font-semibold text-theme-on-surface">Semantic tones</native:text>
            <firstlight:status-label label="Draft" />
            <firstlight:status-label label="In progress" tone="info" />
            <firstlight:status-label label="Ready" tone="success" />
            <firstlight:status-label label="Awaiting review" tone="warning" />
            <firstlight:status-label label="Blocked" tone="danger" />
        </native:column>

        <native:column class="w-full gap-3">
            <native:text class="text-sm font-semibold text-theme-on-surface">Long and large text</native:text>
            <firstlight:status-label
                label="Referral status: awaiting review from the referrals team"
                tone="warning"
            />
        </native:column>

        <native:column class="w-full gap-3 pb-6">
            <native:text class="text-sm font-semibold text-theme-on-surface">Accessibility override</native:text>
            <firstlight:status-label
                label="Screen-reader override"
                tone="success"
                a11y-label="Referral status: ready"
                a11y-hint="Updated by the referrals team"
            />
        </native:column>
    </native:column>
</native:scroll-view>
