<native:scroll-view class="w-full flex-1 bg-theme-background">
    <native:column class="w-full p-4 gap-5 pb-8">
        <native:text class="text-sm text-theme-on-surface">On-device checks: these labels are display-only. Check each tone in both appearances, long-label wrapping at large text sizes, and the final accessible label Referral status: ready. Nothing should gain button behaviour.</native:text>
        <native:column class="w-full gap-3">
            <native:text class="text-sm font-semibold text-theme-on-surface">Semantic tones</native:text>
            <firstlight:status-label label="Draft" />
            <firstlight:status-label label="In progress" tone="info" icon="info" icon-ios="info.circle" icon-android="info" />
            <firstlight:status-label label="Ready" tone="success" icon="check_circle" icon-ios="checkmark.circle" icon-android="check_circle" />
            <firstlight:status-label label="Awaiting review" tone="warning" icon="warning" icon-ios="exclamationmark.triangle" icon-android="warning" />
            <firstlight:status-label label="Blocked" tone="danger" icon="error" icon-ios="xmark.circle" icon-android="error" />
        </native:column>

        <native:column class="w-full gap-3">
            <native:text class="text-sm font-semibold text-theme-on-surface">Long and large text</native:text>
            <firstlight:status-label
                label="Referral status: awaiting review from the referrals team"
                tone="warning"
                icon="warning"
                icon-ios="exclamationmark.triangle"
                icon-android="warning"
            />
        </native:column>

        <native:column class="w-full gap-3 pb-6">
            <native:text class="text-sm font-semibold text-theme-on-surface">Accessibility override</native:text>
            <firstlight:status-label
                label="Screen-reader override"
                tone="success"
                icon="check_circle"
                icon-ios="checkmark.circle"
                icon-android="check_circle"
                a11y-label="Referral status: ready"
                a11y-hint="Updated by the referrals team"
            />
        </native:column>
    </native:column>
</native:scroll-view>
