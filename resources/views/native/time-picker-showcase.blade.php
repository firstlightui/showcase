<native:scroll-view class="w-full flex-1 bg-theme-background">
    <native:column class="w-full p-4 gap-5 pb-8">
        <native:text class="text-sm text-theme-on-surface">On-device checks: choose and cancel times; only confirmed times update PHP. Check Berlin 24-hour and New York 12-hour formatting, the label-less Medication time, dismissal when PHP changes an open picker, disabled input, rejected snap-back and publish/clear below. Values are wall-clock times.</native:text>
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
            <native:text class="text-sm text-theme-on-surface">PHP accepted state: {{ $appointmentTime ?? '(empty)' }}; review: {{ $reviewTime ?? '(empty)' }}</native:text>
        </native:column>

        <native:column class="w-full gap-2">
            <native:text class="text-sm font-semibold text-theme-on-surface">Validation and context</native:text>
            <native:text class="text-sm text-theme-on-surface">Static error presentation example; editing does not clear the message. Use Validation for real rules.</native:text>
            <firstlight:time-picker
                native:model="errorTime"
                label="Arrival time"
                placeholder="Choose an arrival time"
                error="Choose an arrival time."
                required
            />
            <native:text class="text-sm text-theme-on-surface">PHP accepted state: {{ $errorTime ?? '(empty)' }}</native:text>
            <firstlight:time-picker
                native:model="berlinTime"
                label="Berlin clinic time"
                helper="German display locale with a Berlin current-time context."
                locale="de-DE"
                timezone="Europe/Berlin"
            />
            <native:text class="text-sm text-theme-on-surface">PHP accepted state: {{ $berlinTime ?? '(empty)' }}</native:text>
            <firstlight:time-picker
                native:model="newYorkTime"
                label="New York clinic time"
                helper="US English shows a 12-hour clock; PHP still holds 24-hour HH:mm."
                locale="en-US"
                timezone="America/New_York"
            />
            <native:text class="text-sm text-theme-on-surface">PHP accepted state: {{ $newYorkTime ?? '(empty)' }} (12-hour display)</native:text>
            <firstlight:time-picker
                value="20:15"
                label="Disabled time"
                helper="Time changes are unavailable."
                disabled
            />
        </native:column>

        <native:column class="w-full gap-2">
            <native:text class="text-sm font-semibold text-theme-on-surface">Accessibility label only</native:text>
            <native:text class="text-sm text-theme-on-surface">No visible label: VoiceOver and TalkBack name this field Medication time.</native:text>
            <firstlight:time-picker
                native:model="medicationTime"
                a11y-label="Medication time"
                a11y-hint="Choose when the next dose is due"
                placeholder="Choose a dose time"
            />
            <native:text class="text-sm text-theme-on-surface">PHP accepted state: {{ $medicationTime ?? '(empty)' }}</native:text>
        </native:column>

        <native:column class="w-full gap-2">
            <native:text class="text-sm font-semibold text-theme-on-surface">Changes while open</native:text>
            <native:text class="text-sm text-theme-on-surface">Tap a Change button, then open Open time picker and move the draft. About 3 s later PHP changes the value or disables the field: the picker must dismiss and discard the draft without an event.</native:text>
            <firstlight:time-picker
                ref="openTime"
                native:model="openTime"
                label="Open time picker"
                :disabled="$openDisabled"
            />
            <native:text class="text-sm text-theme-on-surface">PHP accepted state: {{ $openTime ?? '(empty)' }}; disabled: {{ $openDisabled ? 'yes' : 'no' }}; pending: {{ $pendingOpenChange ?? 'none' }}; last change: {{ $lastOpenChange }}</native:text>
            <native:row class="w-full gap-2">
                <native:button label="Change time" variant="secondary" @press="scheduleOpenChange('value')" />
                <native:button label="Disable" variant="secondary" @press="scheduleOpenChange('disabled')" />
                <native:button label="Reset open picker" variant="ghost" @press="resetOpenChange" />
            </native:row>
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
            <native:text class="text-sm text-theme-on-surface">PHP accepted state: {{ $approvedTime }}; Rejected attempts: {{ $rejectedAttempts }}</native:text>
            <firstlight:time-picker
                ref="programmaticTime"
                native:model="programmaticTime"
                label="Programmatic time"
                placeholder="No published time"
                a11y-hint="Use the buttons below to replace or clear the published value"
            />
            <native:text class="text-sm text-theme-on-surface">PHP accepted state: {{ $programmaticTime ?? '(empty)' }}</native:text>
            <native:row class="w-full gap-2">
                <native:button label="Publish later time" variant="secondary" @press="publishLaterTime" />
                <native:button label="Clear time" variant="ghost" @press="clearProgrammaticTime" />
            </native:row>
        </native:column>
    </native:column>
</native:scroll-view>
