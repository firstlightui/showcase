<native:scroll-view class="w-full flex-1 bg-theme-background">
    <native:column class="w-full p-5 gap-4">
        <native:text class="text-lg font-semibold text-theme-on-surface">Feedback survives navigation</native:text>
        <native:text class="text-sm text-theme-on-surface-variant">The held item belongs to the app-level feedback service, not the previous screen.</native:text>
        <native:button ref="removeNavigationFeedback" label="Remove navigation feedback" variant="secondary" @press="removeNavigationFeedback" />
        <native:text class="text-sm text-theme-on-surface-variant">Programmatic removal: {{ $lastRemovalSucceeded ? 'removed' : 'not requested' }}</native:text>
        <native:text class="text-sm font-semibold text-theme-on-surface">Most recent feedback event</native:text>
        <native:text class="text-sm text-theme-on-surface-variant">{{ $feedbackEventSummary }}</native:text>
    </native:column>
</native:scroll-view>
