<native:scroll-view class="w-full flex-1 bg-theme-background">
    <native:column class="w-full p-4 pb-8 gap-5">
        <native:text class="text-sm font-semibold text-theme-on-surface">On-device checks</native:text>
        <native:text class="text-sm text-theme-on-surface-variant">Confirm increments confirmed only; cancel increments cancelled only. Open the timed example and wait without tapping: it closes after about 3 seconds with both counts unchanged. If async is unavailable, choose normally and check the outcome below. Check large text and both appearances.</native:text>
        <native:column class="w-full gap-3">
            <native:text class="text-sm font-semibold text-theme-on-surface">Default confirmation</native:text>
            <native:button ref="showDefault" label="Review changes" variant="primary" @press="showDefault" />
        </native:column>

        <native:column class="w-full gap-3">
            <native:text class="text-sm font-semibold text-theme-on-surface">Destructive confirmation</native:text>
            <native:button ref="showDestructive" label="Delete appointment" variant="destructive" @press="showDestructive" />
        </native:column>

        <native:column class="w-full gap-3">
            <native:text class="text-sm font-semibold text-theme-on-surface">Long copy and large text</native:text>
            <native:button ref="showLongCopy" label="Replace care plan" variant="secondary" @press="showLongCopy" />
        </native:column>

        <native:column class="w-full gap-3">
            <native:text class="text-sm font-semibold text-theme-on-surface">Programmatic reconciliation</native:text>
            <native:button ref="showProgrammatic" label="Open timed close example" variant="secondary" @press="showTimedProgrammatic" />
            <native:text class="text-sm text-theme-on-surface-variant">{{ $lastOutcome }} · {{ $confirmationCount }} confirmed · {{ $dismissalCount }} cancelled</native:text>
        </native:column>

        <firstlight:confirmation-dialog
            ref="defaultDialog"
            :visible="$defaultVisible"
            title="Apply these changes?"
            message="The updated appointment details will be visible to the care team."
            confirm-label="Apply changes"
            cancel-label="Keep editing"
            @press="confirmDefault"
            @dismiss="dismissDefault"
        />

        <firstlight:confirmation-dialog
            ref="destructiveDialog"
            :visible="$destructiveVisible"
            title="Delete appointment?"
            message="This removes the appointment and cannot be undone."
            confirm-label="Delete appointment"
            cancel-label="Keep appointment"
            tone="destructive"
            @press="confirmDestructive"
            @dismiss="dismissDestructive"
        />

        <firstlight:confirmation-dialog
            ref="longCopyDialog"
            :visible="$longCopyVisible"
            title="Replace the current multidisciplinary care plan with this newly reviewed version?"
            message="The existing plan will remain in the clinical audit history, but everyone assigned to this patient will immediately use the replacement for future work."
            confirm-label="Replace care plan"
            cancel-label="Keep current plan"
            tone="destructive"
            @press="confirmLongCopy"
            @dismiss="dismissLongCopy"
        />

        <firstlight:confirmation-dialog
            ref="programmaticDialog"
            :visible="$programmaticVisible"
            title="Programmatic closure"
            message="Wait without tapping. PHP will close this dialog after about 3 seconds without recording a confirmation or cancellation."
            confirm-label="Confirm example"
            cancel-label="Cancel example"
            @press="confirmProgrammatic"
            @dismiss="dismissProgrammatic"
        />
    </native:column>
</native:scroll-view>
