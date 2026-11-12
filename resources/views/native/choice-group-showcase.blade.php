<native:scroll-view class="w-full flex-1 bg-theme-background">
    <native:column class="w-full p-4 gap-5 pb-8">
        <native:text class="text-sm text-theme-on-surface">On-device checks: change one priority, toggle multiple notifications, then reset to Routine and Email/Push. Integer triage preserves identity; unavailable options cannot be chosen. Clear priority publishes null from PHP and leaves every row unselected. Locked priority and Locked notifications keep their values and count rejections. Choosing Critical in Validated priority shows a Laravel message. Long labels should wrap and remain selectable.</native:text>
        <native:column class="w-full gap-2">
            <native:text class="text-sm font-semibold text-theme-on-surface">Single radio choice</native:text>
            <firstlight:choice-group
                :options="$priorityOptions"
                native:model="priority"
                label="Priority"
                helper="Choose one priority."
                a11y-hint="Select one priority"
                required
            />
            <native:text class="text-sm text-theme-on-surface">PHP accepted state: {{ $priority ?? '(empty)' }}</native:text>
            <native:button label="Clear priority" variant="secondary" @press="clearPriority" />
        </native:column>

        <native:column class="w-full gap-2">
            <native:text class="text-sm font-semibold text-theme-on-surface">Multiple checkbox choices</native:text>
            <firstlight:choice-group
                :options="$notificationOptions"
                native:model="notifications"
                label="Notifications"
                helper="Choose any that apply."
                multiple
            />
            <native:text class="text-sm text-theme-on-surface">PHP accepted state: {{ implode(', ', $notifications) ?: '(empty)' }}</native:text>
            <native:button label="Reset choices" variant="secondary" @press="resetChoices" />
        </native:column>

        <native:column class="w-full gap-2">
            <native:text class="text-sm font-semibold text-theme-on-surface">Stable integer values</native:text>
            <firstlight:choice-group
                :options="$triageOptions"
                native:model="triageLevel"
                label="Triage level"
                helper="Urgent review is unavailable."
            />
            <native:text class="text-sm text-theme-on-surface">PHP accepted state: {{ $triageLevel }}</native:text>
        </native:column>

        <native:column class="w-full gap-2">
            <native:text class="text-sm font-semibold text-theme-on-surface">Server rejection</native:text>
            <firstlight:choice-group
                :options="$priorityOptions"
                :value="$rejectedPriority"
                @change="rejectPriority"
                label="Locked priority"
                helper="The server keeps Routine selected."
            />
            <native:text class="text-sm text-theme-on-surface">PHP accepted state: {{ $rejectedPriority }}; Rejected attempts: {{ $rejectedAttempts }}</native:text>
            <firstlight:choice-group
                :options="$notificationOptions"
                :value="$lockedNotifications"
                @change="rejectNotifications"
                label="Locked notifications"
                helper="Multiple mode: the server keeps only Email selected."
                multiple
            />
            <native:text class="text-sm text-theme-on-surface">PHP accepted state: {{ implode(', ', $lockedNotifications) }}; Rejected multiple attempts: {{ $rejectedMultipleAttempts }}</native:text>
        </native:column>

        <native:column class="w-full gap-2">
            <native:text class="text-sm font-semibold text-theme-on-surface">Laravel validation with native:model.live</native:text>
            <native:text class="text-sm text-theme-on-surface">Choose Critical: PHP accepts it, validateOnly fails, and the message binds to this group without an error attribute. Choose Routine or Urgent to clear it.</native:text>
            <firstlight:choice-group
                :options="$priorityOptions"
                native:model.live="validatedPriority"
                label="Validated priority"
            />
            <native:text class="text-sm text-theme-on-surface">PHP accepted state: validated {{ $validatedPriority ?? '(empty)' }}</native:text>
        </native:column>

        <native:column class="w-full gap-2">
            <native:text class="text-sm font-semibold text-theme-on-surface">Accessibility name, absent value and empty options</native:text>
            <native:text class="text-sm text-theme-on-surface">The first group has no visible label; VoiceOver and TalkBack read its a11y-label, Unlabelled priority.</native:text>
            <firstlight:choice-group
                :options="$priorityOptions"
                native:model="unlabelledPriority"
                a11y-label="Unlabelled priority"
            />
            <firstlight:choice-group
                :options="$priorityOptions"
                native:model="absentPriority"
                label="Deferred priority"
                helper="PHP holds deferred, which is not an option, so no row is selected."
            />
            <native:text class="text-sm text-theme-on-surface">PHP accepted state: unlabelled {{ $unlabelledPriority ?? '(empty)' }}; absent {{ $absentPriority ?? '(empty)' }}</native:text>
            <firstlight:choice-group
                :options="$emptyOptions"
                :value="null"
                label="No available priorities"
                helper="An empty option list is inert."
            />
        </native:column>

        <native:column class="w-full gap-2">
            <native:text class="text-sm font-semibold text-theme-on-surface">Validation and disabled state</native:text>
            <native:text class="text-sm text-theme-on-surface">Static error presentation example; editing does not clear the message. Use Validation for real rules. Disabled priority stays Urgent.</native:text>
            <firstlight:choice-group
                :options="$priorityOptions"
                native:model="errorPriority"
                label="Priority with error"
                helper="Choose a priority."
                error="Priority is required."
                required
            />
            <native:text class="text-sm text-theme-on-surface">PHP accepted state: {{ $errorPriority ?? '(empty)' }}</native:text>
            <firstlight:choice-group
                :options="$priorityOptions"
                :value="'urgent'"
                label="Disabled priority"
                disabled
            />
        </native:column>

        <native:column class="w-full gap-2">
            <firstlight:choice-group
                ref="favourites"
                variant="tiles"
                :options="[
                    ['value' => 'consult', 'prefix' => '110', 'label' => 'Initial consultation', 'supporting' => '$90.00'],
                    ['value' => 'review', 'prefix' => '116', 'label' => 'Follow-up', 'supporting' => '$45.00'],
                    ['value' => 'unavailable', 'prefix' => '119', 'label' => 'Unavailable', 'supporting' => '$65.00', 'disabled' => true],
                ]"
                native:model="favourites"
                label="Favourites"
                multiple
            />
            <native:text class="text-sm text-theme-on-surface">Accepted favourites: {{ implode(', ', $favourites) }}</native:text>
        </native:column>

        <native:column class="w-full gap-2 pb-6">
            <native:text class="text-sm font-semibold text-theme-on-surface">Long labels</native:text>
            <firstlight:choice-group
                :options="$longOptions"
                native:model="nextStep"
                label="Next step"
                a11y-hint="Select the next workflow step"
            />
            <native:text class="text-sm text-theme-on-surface">PHP accepted state: {{ $nextStep ?? '(empty)' }}</native:text>
        </native:column>
    </native:column>
</native:scroll-view>
