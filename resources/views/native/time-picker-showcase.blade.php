<native:scroll-view class="w-full flex-1 bg-theme-background">
    <native:column class="w-full p-4 gap-5">
        <native:column class="w-full gap-2">
            <native:text class="text-sm font-semibold text-theme-on-surface">Empty and accepted times</native:text>
            <firstlight:time-picker
                ref="appointmentTime"
                native:model="appointmentTime"
                label="Appointment time"
                placeholder="Choose a time"
                helper="Uses the clinic's local clock."
                locale="en-AU"
                timezone="Australia/Sydney"
            />
            <firstlight:time-picker
                ref="reviewTime"
                native:model="reviewTime"
                label="Review time"
                helper="A required accepted wall-clock time."
                locale="en-AU"
                timezone="Australia/Sydney"
                required
            />
        </native:column>

        <native:column class="w-full gap-2">
            <native:text class="text-sm font-semibold text-theme-on-surface">Validation and context</native:text>
            <firstlight:time-picker
                label="Arrival time"
                placeholder="Choose an arrival time"
                error="Choose an arrival time."
                required
            />
            <firstlight:time-picker
                value="18:30"
                label="Berlin clinic time"
                helper="German display locale with a Berlin current-time context."
                locale="de-DE"
                timezone="Europe/Berlin"
            />
            <firstlight:time-picker
                value="20:15"
                label="Disabled time"
                helper="Time changes are unavailable."
                disabled
            />
        </native:column>

        <native:column class="w-full gap-2 pb-6">
            <native:text class="text-sm font-semibold text-theme-on-surface">Reconciliation</native:text>
            <firstlight:time-picker
                ref="rejectedTime"
                :value="$approvedTime"
                label="Server-approved time"
                helper="New proposals are deliberately rejected by PHP."
                @change="rejectTime"
            />
            <firstlight:time-picker
                ref="programmaticTime"
                native:model="programmaticTime"
                label="Programmatic time"
                placeholder="No published time"
                a11y-hint="Use the buttons below to replace or clear the published value"
            />
            <native:row class="w-full gap-2">
                <native:button label="Publish later time" variant="secondary" @press="publishLaterTime" />
                <native:button label="Clear time" variant="ghost" @press="clearProgrammaticTime" />
            </native:row>
        </native:column>
    </native:column>
</native:scroll-view>
