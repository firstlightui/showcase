<native:scroll-view class="w-full flex-1 bg-theme-background">
    <native:column class="w-full p-4 gap-5 pb-8">
        <native:text class="text-sm text-theme-on-surface">On-device checks: show every tone, activate Undo and inspect the latest event, archive the item and Undo it from the feedback (Item returns to active), hold and remove feedback, and queue three messages. Dismiss Updated in place to reveal Still second. Navigate with held feedback, remove it on the destination, then use native Back. Repeat with a keyboard open and a screen reader.</native:text>
        <native:column class="w-full gap-3">
            <native:text class="text-sm font-semibold text-theme-on-surface">Message-only tones</native:text>
            <native:button ref="publishDefault" label="Show neutral" variant="secondary" @press="publishDefault" />
            <native:button ref="publishSuccess" label="Show success" variant="secondary" @press="publishSuccess" />
            <native:button ref="publishWarning" label="Show warning" variant="secondary" @press="publishWarning" />
            <native:button ref="publishDanger" label="Show danger" variant="destructive" @press="publishDanger" />
        </native:column>

        <native:column class="w-full gap-3">
            <native:text class="text-sm font-semibold text-theme-on-surface">Action and lifetime</native:text>
            <native:button ref="publishAction" label="Show Undo action" variant="primary" @press="publishAction" />
            <native:button ref="publishHeld" label="Hold until dismissed" variant="secondary" @press="publishHeld" />
            <native:button ref="removeHeld" label="Remove held feedback" variant="secondary" @press="removeHeld" />
            <native:text class="text-sm text-theme-on-surface-variant">Programmatic removal: {{ $lastRemovalSucceeded ? 'removed' : 'not requested' }}</native:text>
        </native:column>

        <native:column class="w-full gap-3">
            <native:text class="text-sm font-semibold text-theme-on-surface">Screen-local Undo</native:text>
            <native:button ref="archive" label="Archive item" variant="secondary" @press="archive" />
            <native:text class="text-sm text-theme-on-surface-variant">Item: {{ $archived ? 'archived' : 'active' }}</native:text>
        </native:column>

        <native:column class="w-full gap-3">
            <native:text class="text-sm font-semibold text-theme-on-surface">Queue and stable identity</native:text>
            <native:button ref="queueThree" label="Queue three in FIFO order" variant="secondary" @press="queueThree" />
            <native:button ref="queueStableUpdate" label="Update one stable ID" variant="secondary" @press="queueStableUpdate" />
        </native:column>

        <native:column class="w-full gap-3">
            <native:text class="text-sm font-semibold text-theme-on-surface">Navigation survival</native:text>
            <native:button ref="navigateWithFeedback" label="Show feedback and navigate" variant="primary" @press="navigateWithFeedback" />
        </native:column>

        <native:column class="w-full gap-2">
            <native:text class="text-sm font-semibold text-theme-on-surface">Most recent feedback event</native:text>
            <native:text class="text-sm text-theme-on-surface-variant">{{ $feedbackEventSummary }}</native:text>
        </native:column>
    </native:column>
</native:scroll-view>
