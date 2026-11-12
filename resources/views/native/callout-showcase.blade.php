<native:scroll-view class="w-full flex-1 bg-theme-background">
    <native:column class="w-full p-4 gap-5 pb-8">
        <native:text class="text-sm text-theme-on-surface">On-device checks: inspect every tone and long-message wrapping. Only Review changes is actionable; each activation increments the visible count once. The messages remain until the screen changes. Check labels and hints with a screen reader.</native:text>
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

        <native:column class="w-full gap-3">
            <native:text class="text-sm font-semibold text-theme-on-surface">Programmatic updates</native:text>
            <native:text class="text-sm text-theme-on-surface">PHP republishes the same callout with a new message and tone: info, then success, then danger. Remove callout omits it from the next tree; there is no close button or dismissal event.</native:text>
            <native:text class="text-sm text-theme-on-surface-variant">PHP accepted state: sync {{ $syncStatus }} · callout {{ $syncNoticeShown ? 'shown' : 'removed' }}</native:text>
            @if ($syncNoticeShown)
                <firstlight:callout
                    :message="$this->syncNotice()['message']"
                    :tone="$this->syncNotice()['tone']"
                />
            @endif
            <firstlight:button label="Next sync status" variant="secondary" @press="advanceSyncStatus" />
            @if ($syncNoticeShown)
                <firstlight:button label="Remove callout" variant="secondary" @press="removeSyncNotice" />
            @endif
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
