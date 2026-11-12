<native:scroll-view class="w-full flex-1 bg-theme-background">
    <native:column class="w-full p-4 gap-5 pb-8">
        <native:text class="text-sm text-theme-on-surface">On-device checks: these badges are display-only, not buttons. Compare zero, one, 99 and overflow 100; the overflow display is 99+ but the accessible count is 100. Check every tone, large text, and the Prescription ready accessibility override.</native:text>
        <native:column class="w-full gap-3">
            <native:text class="text-sm font-semibold text-theme-on-surface">Count formatting</native:text>
            <firstlight:badge :count="0" a11y-label="No unread messages" />
            <firstlight:badge :count="1" a11y-label="1 unread message" />
            <firstlight:badge :count="9" tone="info" a11y-label="9 unread messages" />
            <firstlight:badge :count="99" tone="warning" a11y-label="99 pending items" />
            <firstlight:badge :count="100" tone="danger" a11y-label="100 pending items" />
        </native:column>

        <native:column class="w-full gap-3">
            <native:text class="text-sm font-semibold text-theme-on-surface">Semantic tones</native:text>
            <firstlight:badge label="New" />
            <firstlight:badge label="Info" tone="info" />
            <firstlight:badge label="Ready" tone="success" />
            <firstlight:badge label="Wait" tone="warning" />
            <firstlight:badge label="Alert" tone="danger" />
        </native:column>

        <native:column class="w-full gap-3">
            <native:text class="text-sm font-semibold text-theme-on-surface">Programmatic updates</native:text>
            <native:text class="text-sm text-theme-on-surface">PHP replaces the count, label and tone of the same badges. Add 49 reaches 99+ on the third press; Clear hides the count badge. No callback is produced.</native:text>
            <native:text class="text-sm text-theme-on-surface">PHP accepted state: unread {{ $unreadCount }} · status {{ $prescriptionReady ? 'Ready' : 'Wait' }}</native:text>
            <firstlight:badge
                :count="$unreadCount"
                tone="danger"
                a11y-label="{{ $unreadCount }} unread messages"
            />
            <firstlight:badge
                :label="$prescriptionReady ? 'Ready' : 'Wait'"
                :tone="$prescriptionReady ? 'success' : 'warning'"
            />
            <firstlight:button label="Add 49 unread" variant="secondary" @press="addUnread" />
            <firstlight:button label="Clear unread" variant="secondary" @press="clearUnread" />
            <firstlight:button label="Toggle status label" variant="secondary" @press="togglePrescription" />
        </native:column>

        <native:column class="w-full gap-3">
            <native:text class="text-sm font-semibold text-theme-on-surface">Warning colours after Theme::merge()</native:text>
            <native:text class="text-sm text-theme-on-surface">Apply merges a darker warning pair into the app theme and republishes this screen, so the warning badge refreshes. The merge is app-wide until you restore the default pair.</native:text>
            <native:text class="text-sm text-theme-on-surface">PHP accepted state: warning colours {{ $customWarning ? 'custom' : 'default' }}</native:text>
            <firstlight:badge label="Pending" tone="warning" />
            @if ($customWarning)
                <firstlight:button label="Restore default warning colours" variant="secondary" @press="restoreWarning" />
            @else
                <firstlight:button label="Apply custom warning colours" variant="secondary" @press="applyCustomWarning" />
            @endif
        </native:column>

        <native:column class="w-full gap-3 pb-6">
            <native:text class="text-sm font-semibold text-theme-on-surface">Accessibility override</native:text>
            <firstlight:badge
                label="Rx"
                tone="success"
                a11y-label="Prescription ready"
                a11y-hint="Open the prescription to review it"
            />
        </native:column>
    </native:column>
</native:scroll-view>
