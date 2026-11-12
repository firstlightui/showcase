<native:scroll-view class="w-full flex-1 bg-theme-background">
    <native:column class="w-full p-4 pb-8 gap-5">
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
            <native:text class="text-sm font-semibold text-theme-on-surface">Package chrome label</native:text>
            <native:text class="text-sm text-theme-on-surface-variant">This alert omits action-label, so the action shows the package chrome string OK in the app locale.</native:text>
            <native:button ref="showPackageLabel" label="Show package label alert" variant="secondary" @press="showPackageLabel" />
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

        <native:column class="w-full gap-3">
            <native:text class="text-sm font-semibold text-theme-on-surface">Copy-only republication</native:text>
            <native:text class="text-sm text-theme-on-surface-variant">Open the alert and wait: after about 3 seconds PHP republishes only its message and the alert stays open. After acknowledging, Republish copy while closed changes the message again; the alert must not reopen.</native:text>
            <native:button ref="showCopyUpdate" label="Open copy update example" variant="secondary" @press="showCopyUpdate" />
            <native:button ref="republishCopy" label="Republish copy while closed" variant="secondary" @press="republishCopy" />
            <native:text class="text-sm text-theme-on-surface-variant">PHP state: copy alert {{ $copyVisible ? 'open' : 'closed' }} · copy revision {{ $copyRevision }}</native:text>
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
            ref="packageLabelDialog"
            :visible="$packageLabelVisible"
            title="Reminder sent"
            message="The patient will receive the reminder shortly."
            @dismiss="acknowledgePackageLabel"
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

        <firstlight:alert-dialog
            ref="copyUpdateDialog"
            :visible="$copyVisible"
            title="Copy update"
            :message="$this->copyMessage()"
            action-label="OK"
            @dismiss="acknowledgeCopyUpdate"
        />
    </native:column>
</native:scroll-view>
