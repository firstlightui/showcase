<native:scroll-view class="w-full flex-1 bg-theme-background">
    <native:column class="w-full p-4 pb-24 gap-5">
        <native:text class="text-sm font-semibold text-theme-on-surface">On-device checks</native:text>
        <native:text class="text-sm text-theme-on-surface-variant">OK or Got it increments acknowledged once. Open the timed example and wait without tapping: it closes after about 3 seconds with acknowledged unchanged. If async is unavailable, acknowledge normally and check the outcome below. Repeat with large text and both appearances.</native:text>
        <native:column class="w-full gap-3">
            <native:text class="text-sm font-semibold text-theme-on-surface">Default acknowledgement</native:text>
            <native:button ref="showDefault" label="Show saved alert" variant="primary" @press="showDefault" />
        </native:column>

        <native:column class="w-full gap-3">
            <native:text class="text-sm font-semibold text-theme-on-surface">Custom action label</native:text>
            <native:button ref="showCustomAction" label="Show custom action" variant="secondary" @press="showCustomAction" />
        </native:column>

        <native:column class="w-full gap-3">
            <native:text class="text-sm font-semibold text-theme-on-surface">Long copy and large text</native:text>
            <native:button ref="showLongCopy" label="Show long copy" variant="secondary" @press="showLongCopy" />
        </native:column>

        <native:column class="w-full gap-3">
            <native:text class="text-sm font-semibold text-theme-on-surface">Programmatic reconciliation</native:text>
            <native:button ref="showProgrammatic" label="Open timed close example" variant="secondary" @press="showTimedProgrammatic" />
            <native:text class="text-sm text-theme-on-surface-variant">{{ $lastOutcome }} · {{ $acknowledgementCount }} acknowledged</native:text>
        </native:column>

        <firstlight:alert-dialog
            ref="defaultDialog"
            :visible="$defaultVisible"
            title="Changes saved"
            message="Your profile was updated."
            action-label="OK"
            @dismiss="acknowledgeDefault"
        />

        <firstlight:alert-dialog
            ref="customActionDialog"
            :visible="$customActionVisible"
            title="Download complete"
            message="The export is ready to share."
            action-label="Got it"
            @dismiss="acknowledgeCustomAction"
        />

        <firstlight:alert-dialog
            ref="longCopyDialog"
            :visible="$longCopyVisible"
            title="Your appointment details were updated for everyone assigned to this visit"
            message="The previous times remain in the audit history, but the visible schedule now uses the replacement for all future work."
            action-label="OK"
            @dismiss="acknowledgeLongCopy"
        />

        <firstlight:alert-dialog
            ref="programmaticDialog"
            :visible="$programmaticVisible"
            title="Programmatic closure"
            message="Wait without tapping. PHP will close this alert after about 3 seconds without recording an acknowledgement."
            action-label="OK"
            @dismiss="acknowledgeProgrammatic"
        />
    </native:column>
</native:scroll-view>
