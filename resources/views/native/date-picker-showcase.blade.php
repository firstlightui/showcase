<native:scroll-view class="w-full flex-1 bg-theme-background">
    <native:column class="w-full p-4 gap-5">
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
        </native:column>

        <native:column class="w-full gap-2">
            <native:text class="text-sm font-semibold text-theme-on-surface">Validation and context</native:text>
            <firstlight:date-picker
                label="Discharge date"
                placeholder="Choose a discharge date"
                error="Choose a discharge date."
                required
            />
            <firstlight:date-picker
                value="2026-12-24"
                label="Berlin clinic date"
                helper="German display locale with a Berlin calendar context."
                locale="de-DE"
                timezone="Europe/Berlin"
            />
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
            <firstlight:date-picker
                ref="programmaticDate"
                native:model="programmaticDate"
                label="Programmatic date"
                placeholder="No published date"
                a11y-hint="Use the buttons below to replace or clear the published value"
            />
            <native:row class="w-full gap-2">
                <native:button label="Publish later date" variant="secondary" @press="publishLaterDate" />
                <native:button label="Clear date" variant="ghost" @press="clearProgrammaticDate" />
            </native:row>
        </native:column>
    </native:column>
</native:scroll-view>
