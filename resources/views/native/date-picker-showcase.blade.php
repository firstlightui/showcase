<native:scroll-view class="w-full flex-1 bg-theme-background">
    <native:column class="w-full p-4 gap-5 pb-8">
        <native:text class="text-sm text-theme-on-surface">On-device checks: choose and cancel dates; only confirmed dates update PHP. Review stays within August 2026. Check Berlin formatting, disabled input, rejected snap-back and publish/clear below.</native:text>
        <native:column class="w-full gap-2">
            <native:text class="text-sm font-semibold text-theme-on-surface">Empty and bounded dates</native:text>
            <firstlight:date-picker
                ref="appointmentDate"
                native:model="appointmentDate"
                label="Appointment date"
                placeholder="Choose a date"
                helper="Uses the clinic's local calendar."
                locale="en-AU"
                timezone="Australia/Sydney"
            />
            <firstlight:date-picker
                ref="boundedDate"
                native:model="boundedDate"
                label="Review date"
                helper="Choose a date during August 2026."
                min="2026-08-01"
                max="2026-08-31"
                locale="en-AU"
                timezone="Australia/Sydney"
                required
            />
            <native:text class="text-sm text-theme-on-surface">PHP accepted state: {{ $appointmentDate ?? '(empty)' }}; review: {{ $boundedDate ?? '(empty)' }}</native:text>
        </native:column>

        <native:column class="w-full gap-2">
            <native:text class="text-sm font-semibold text-theme-on-surface">Validation and context</native:text>
            <native:text class="text-sm text-theme-on-surface">Static error presentation example; editing does not clear the message. Use Validation for real rules.</native:text>
            <firstlight:date-picker
                native:model="errorDate"
                label="Discharge date"
                placeholder="Choose a discharge date"
                error="Choose a discharge date."
                required
            />
            <native:text class="text-sm text-theme-on-surface">PHP accepted state: {{ $errorDate ?? '(empty)' }}</native:text>
            <firstlight:date-picker
                native:model="berlinDate"
                label="Berlin clinic date"
                helper="German display locale with a Berlin calendar context."
                locale="de-DE"
                timezone="Europe/Berlin"
            />
            <native:text class="text-sm text-theme-on-surface">PHP accepted state: {{ $berlinDate ?? '(empty)' }}</native:text>
            <firstlight:date-picker
                value="2026-08-20"
                label="Disabled date"
                helper="Date changes are unavailable."
                disabled
            />
        </native:column>

        <native:column class="w-full gap-2 pb-6">
            <native:text class="text-sm font-semibold text-theme-on-surface">Reconciliation</native:text>
            <firstlight:date-picker
                ref="rejectedDate"
                :value="$approvedDate"
                label="Server-approved date"
                helper="New proposals are deliberately rejected by PHP."
                @change="rejectDate"
            />
            <native:text class="text-sm text-theme-on-surface">PHP accepted state: {{ $approvedDate }}; Rejected attempts: {{ $rejectedAttempts }}</native:text>
            <firstlight:date-picker
                ref="programmaticDate"
                native:model="programmaticDate"
                label="Programmatic date"
                placeholder="No published date"
                a11y-hint="Use the buttons below to replace or clear the published value"
            />
            <native:text class="text-sm text-theme-on-surface">PHP accepted state: {{ $programmaticDate ?? '(empty)' }}</native:text>
            <native:row class="w-full gap-2">
                <native:button label="Publish later date" variant="secondary" @press="publishLaterDate" />
                <native:button label="Clear date" variant="ghost" @press="clearProgrammaticDate" />
            </native:row>
        </native:column>
    </native:column>
</native:scroll-view>
