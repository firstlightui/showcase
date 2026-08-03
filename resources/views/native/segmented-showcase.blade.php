<native:scroll-view class="w-full flex-1 bg-theme-background">
    <native:column class="w-full p-4 gap-5">
        <native:column class="w-full gap-2">
            <native:text class="text-sm font-semibold text-theme-on-surface">Stable integer values</native:text>
            <firstlight:segmented
                :options="$priorityOptions"
                native:model="priority"
                label="Priority"
                helper="Routine is selected; Urgent is unavailable."
                a11y-hint="Choose a referral priority"
            />
        </native:column>

        <native:column class="w-full gap-2">
            <native:text class="text-sm font-semibold text-theme-on-surface">Server reconciliation</native:text>
            <firstlight:segmented
                :options="$queueOptions"
                native:model="queue"
                label="Queue"
                helper="Change the queue, then reset it from the server."
                a11y-hint="Choose the active queue"
            />
            <native:button label="Reset selections" variant="primary" @press="resetSelections" />
        </native:column>

        <native:column class="w-full gap-2">
            <native:text class="text-sm font-semibold text-theme-on-surface">Simple strings</native:text>
            <firstlight:segmented
                :options="$simpleOptions"
                native:model="simple"
                label="Simple queue"
            />
        </native:column>

        <native:column class="w-full gap-2">
            <native:text class="text-sm font-semibold text-theme-on-surface">Stable string values</native:text>
            <firstlight:segmented
                :options="$queueOptions"
                native:model="queue"
                label="Stable queue"
            />
        </native:column>

        <native:column class="w-full gap-2">
            <native:text class="text-sm font-semibold text-theme-on-surface">Null and empty states</native:text>
            <firstlight:segmented
                :options="$queueOptions"
                native:model="unselected"
                label="No initial selection"
                helper="Null leaves every segment unselected."
            />
            <firstlight:segmented
                :options="$emptyOptions"
                :value="null"
                label="No available options"
                helper="An empty option list is inert."
            />
        </native:column>

        <native:column class="w-full gap-2">
            <native:text class="text-sm font-semibold text-theme-on-surface">Disabled and error states</native:text>
            <firstlight:segmented
                :options="$disabledOptions"
                native:model="disabledSelection"
                label="Disabled group"
                disabled
            />
            <firstlight:segmented
                :options="$queueOptions"
                native:model="errorSelection"
                label="Queue with error"
                error="Choose a queue before continuing."
            />
        </native:column>

        <native:column class="w-full gap-2">
            <native:text class="text-sm font-semibold text-theme-on-surface">Required and helper text</native:text>
            <firstlight:segmented
                :options="$queueOptions"
                native:model="requiredSelection"
                label="Required queue"
                helper="This selection is required."
                required
            />
        </native:column>

        <native:column class="w-full gap-2 pb-6">
            <native:text class="text-sm font-semibold text-theme-on-surface">Long labels</native:text>
            <firstlight:segmented
                :options="$longOptions"
                native:model="longSelection"
                label="Workflow status"
                a11y-hint="Choose the workflow status"
            />
        </native:column>
    </native:column>
</native:scroll-view>
