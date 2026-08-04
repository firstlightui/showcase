<native:scroll-view class="w-full flex-1 bg-theme-background">
    <native:column class="w-full p-4 gap-5">
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
        </native:column>

        <native:column class="w-full gap-2">
            <native:text class="text-sm font-semibold text-theme-on-surface">Validation and disabled state</native:text>
            <firstlight:choice-group
                :options="$priorityOptions"
                :value="null"
                label="Priority with error"
                helper="Choose a priority."
                error="Priority is required."
                required
            />
            <firstlight:choice-group
                :options="$priorityOptions"
                :value="'urgent'"
                label="Disabled priority"
                disabled
            />
        </native:column>

        <native:column class="w-full gap-2 pb-6">
            <native:text class="text-sm font-semibold text-theme-on-surface">Long labels</native:text>
            <firstlight:choice-group
                :options="$longOptions"
                :value="'consult'"
                label="Next step"
                a11y-hint="Select the next workflow step"
            />
        </native:column>
    </native:column>
</native:scroll-view>
