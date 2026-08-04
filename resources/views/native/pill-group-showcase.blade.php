<native:scroll-view class="w-full flex-1 bg-theme-background">
    <native:column class="w-full p-4 gap-5">
        <native:column class="w-full gap-2">
            <native:text class="text-sm font-semibold text-theme-on-surface">Single selection</native:text>
            <firstlight:pill-group
                :options="$queueOptions"
                native:model="queue"
                label="Queue"
                helper="Tap the selected pill to clear it."
                a11y-hint="Choose zero or one queue"
            />
        </native:column>

        <native:column class="w-full gap-2">
            <native:text class="text-sm font-semibold text-theme-on-surface">Multiple selection</native:text>
            <firstlight:pill-group
                :options="$queueOptions"
                native:model="queues"
                label="Queues"
                helper="Choose any that apply."
                multiple
                required
            />
            <native:button label="Reset selections" variant="secondary" @press="resetSelections" />
        </native:column>

        <native:column class="w-full gap-2">
            <native:text class="text-sm font-semibold text-theme-on-surface">Stable integer values</native:text>
            <firstlight:pill-group
                :options="$priorityOptions"
                native:model="priority"
                label="Priority"
                helper="Urgent is unavailable."
            />
        </native:column>

        <native:column class="w-full gap-2">
            <native:text class="text-sm font-semibold text-theme-on-surface">Server rejection</native:text>
            <firstlight:pill-group
                :options="$queueOptions"
                :value="$rejectedQueue"
                @change="rejectQueue"
                label="Locked queue"
                helper="The server keeps Mine selected."
            />
        </native:column>

        <native:column class="w-full gap-2">
            <native:text class="text-sm font-semibold text-theme-on-surface">Validation and disabled state</native:text>
            <firstlight:pill-group
                :options="$queueOptions"
                :value="null"
                label="Queue with error"
                error="Choose at least one queue."
                required
            />
            <firstlight:pill-group
                :options="$queueOptions"
                :value="'all'"
                label="Disabled queue"
                disabled
            />
        </native:column>

        <native:column class="w-full gap-2 pb-6">
            <native:text class="text-sm font-semibold text-theme-on-surface">Long labels and wrapping</native:text>
            <firstlight:pill-group
                :options="$longOptions"
                :value="'clinical-review'"
                label="Workflow status"
                a11y-hint="Choose a workflow status"
            />
        </native:column>
    </native:column>
</native:scroll-view>
