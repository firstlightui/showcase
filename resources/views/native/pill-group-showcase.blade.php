<native:scroll-view class="w-full flex-1 bg-theme-background">
    <native:column class="w-full p-4 gap-5 pb-8">
        <native:text class="text-sm text-theme-on-surface">On-device checks: tap a selected single pill to clear; toggle multiple queues, then reset to Mine and Mine/Urgent. Integer priority preserves identity; unavailable options cannot be chosen. Locked queue stays Mine and counts rejections. Long pills should wrap and remain selectable.</native:text>
        <native:column class="w-full gap-2">
            <native:text class="text-sm font-semibold text-theme-on-surface">Single selection</native:text>
            <firstlight:pill-group
                :options="$queueOptions"
                native:model="queue"
                label="Queue"
                helper="Tap the selected pill to clear it."
                a11y-hint="Choose zero or one queue"
            />
            <native:text class="text-sm text-theme-on-surface">PHP accepted state: {{ $queue ?? '(empty)' }}</native:text>
        </native:column>

        <native:column class="w-full gap-2">
            <native:text class="text-sm font-semibold text-theme-on-surface">Scrolling filter row</native:text>
            <native:text class="text-sm text-theme-on-surface">Unselected filters show only their icon; the selected filter reveals its label in the success tone once PHP accepts it. Scroll to reach Archived.</native:text>
            <firstlight:pill-group
                :options="$mailFilterOptions"
                native:model="mailFilter"
                a11y-label="Mail filter"
                scrollable
                collapse-labels
                tone="success"
            />
            <native:text class="text-sm text-theme-on-surface">PHP accepted state: {{ $mailFilter ?? '(empty)' }}</native:text>
        </native:column>

        <native:column class="w-full gap-2">
            <native:text class="text-sm font-semibold text-theme-on-surface">Scrolling row with labels</native:text>
            <firstlight:pill-group
                :options="$invoiceStatusOptions"
                native:model="invoiceStatuses"
                label="Invoice status"
                helper="Choose any that apply."
                scrollable
                multiple
                tone="danger"
            />
            <native:text class="text-sm text-theme-on-surface">PHP accepted state: {{ implode(', ', $invoiceStatuses) ?: '(empty)' }}</native:text>
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
            <native:text class="text-sm text-theme-on-surface">PHP accepted state: {{ implode(', ', $queues) ?: '(empty)' }}</native:text>
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
            <native:text class="text-sm text-theme-on-surface">PHP accepted state: {{ $priority ?? '(empty)' }}</native:text>
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
            <native:text class="text-sm text-theme-on-surface">PHP accepted state: {{ $rejectedQueue }}; Rejected attempts: {{ $rejectedAttempts }}</native:text>
        </native:column>

        <native:column class="w-full gap-2">
            <native:text class="text-sm font-semibold text-theme-on-surface">Validation and disabled state</native:text>
            <native:text class="text-sm text-theme-on-surface">Static error presentation example; editing does not clear the message. Use Validation for real rules. Disabled queue stays All.</native:text>
            <firstlight:pill-group
                :options="$queueOptions"
                native:model="errorQueue"
                label="Queue with error"
                error="Choose at least one queue."
                required
            />
            <native:text class="text-sm text-theme-on-surface">PHP accepted state: {{ $errorQueue ?? '(empty)' }}</native:text>
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
                native:model="workflowStatus"
                label="Workflow status"
                a11y-hint="Choose a workflow status"
            />
            <native:text class="text-sm text-theme-on-surface">PHP accepted state: {{ $workflowStatus ?? '(empty)' }}</native:text>
        </native:column>
    </native:column>
</native:scroll-view>
