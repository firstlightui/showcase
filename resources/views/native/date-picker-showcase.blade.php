<native:scroll-view class="w-full flex-1 bg-theme-background">
    <native:column class="w-full p-4 gap-5 pb-8">
        <native:text class="text-sm text-theme-on-surface">On-device checks: choose and cancel dates; only confirmed dates update PHP. Review stays within August 2026. Check Berlin formatting, disabled input, rejected snap-back and publish/clear below. Changes while open: schedule a change, open the picker, and it must dismiss when PHP publishes.</native:text>
        <firstlight:date-picker
            variant="chip"
            native:model="boundedDate"
            label="DOS"
            :display-text="$boundedDate === now()->toDateString() ? 'Today' : \Carbon\CarbonImmutable::parse($boundedDate)->format('j M Y')"
            min="2026-08-01"
            max="2026-08-31"
        />
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

        <native:column class="w-full gap-2">
            <native:text class="text-sm font-semibold text-theme-on-surface">Inherited locale and accessibility label only</native:text>
            <native:text class="text-sm text-theme-on-surface">The first field omits locale and timezone, so it inherits the application's {{ app()->getLocale() }} locale and {{ config('app.timezone') }} timezone. The second has no visible label: VoiceOver and TalkBack name it Follow-up date.</native:text>
            <firstlight:date-picker
                native:model="inheritedDate"
                label="Inherited locale date"
                helper="No locale or timezone attribute."
            />
            <firstlight:date-picker
                native:model="followUpDate"
                a11y-label="Follow-up date"
                a11y-hint="Choose when to review the patient again"
                placeholder="Choose a follow-up date"
            />
            <native:text class="text-sm text-theme-on-surface">PHP accepted state: {{ $inheritedDate ?? '(empty)' }}; follow-up: {{ $followUpDate ?? '(empty)' }}</native:text>
        </native:column>

        <native:column class="w-full gap-2">
            <native:text class="text-sm font-semibold text-theme-on-surface">Changes while open</native:text>
            <native:text class="text-sm text-theme-on-surface">Tap a Change button, then open Open picker and move the draft. About 3 s later PHP changes the value, bounds, or disabled state: the picker must dismiss and discard the draft without an event.</native:text>
            <firstlight:date-picker
                ref="openDate"
                native:model="openDate"
                label="Open picker"
                :min="$openMin"
                :max="$openMax"
                :disabled="$openDisabled"
            />
            <native:text class="text-sm text-theme-on-surface">PHP accepted state: {{ $openDate ?? '(empty)' }}; bounds {{ $openMin }} to {{ $openMax }}; disabled: {{ $openDisabled ? 'yes' : 'no' }}; pending: {{ $pendingOpenChange ?? 'none' }}; last change: {{ $lastOpenChange }}</native:text>
            <native:row class="w-full gap-2">
                <native:button label="Change value" variant="secondary" @press="scheduleOpenChange('value')" />
                <native:button label="Change bounds" variant="secondary" @press="scheduleOpenChange('bounds')" />
                <native:button label="Disable" variant="secondary" @press="scheduleOpenChange('disabled')" />
            </native:row>
            <native:button label="Reset open picker" variant="ghost" @press="resetOpenChange" />
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
