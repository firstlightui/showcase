<native:scroll-view class="w-full flex-1 bg-theme-background">
    <native:column class="w-full p-4 gap-5 pb-32">
        <native:text class="text-sm text-theme-on-surface">On-device checks: choose string and integer options; unavailable triage cannot be chosen. Search both queue pickers and select a result; cancelling leaves PHP unchanged. Disabled priority stays Routine; rejected queue stays All and counts attempts.</native:text>
        <native:column class="w-full gap-2">
            <native:text class="text-sm font-semibold text-theme-on-surface">Compact stable values</native:text>
            <firstlight:select
                ref="priority"
                :options="$priorityOptions"
                native:model="priority"
                label="Priority"
                placeholder="Select a priority"
                helper="A nullable string-backed selection."
            />
            <native:text class="text-sm text-theme-on-surface">PHP accepted state: {{ $priority ?? '(empty)' }}</native:text>
            <firstlight:select
                ref="triageLevel"
                :options="$triageOptions"
                native:model="triageLevel"
                label="Triage level"
                helper="Integer identity is preserved through the callback."
                required
            />
            <native:text class="text-sm text-theme-on-surface">PHP accepted state: {{ $triageLevel }}</native:text>
        </native:column>

        <native:column class="w-full gap-2">
            <native:text class="text-sm font-semibold text-theme-on-surface">Searchable presentation</native:text>
            <firstlight:select
                ref="largeQueue"
                :options="$largeQueueOptions"
                native:model="largeQueue"
                label="Large queue"
                placeholder="Search queues"
                helper="Thirteen options enable search automatically."
            />
            <native:text class="text-sm text-theme-on-surface">PHP accepted state: {{ $largeQueue ?? '(empty)' }}</native:text>
            <firstlight:select
                ref="forcedQueue"
                :options="['mine' => 'Mine', 'all' => 'All']"
                native:model="forcedQueue"
                label="Forced searchable queue"
                helper="Search is explicitly enabled for this small set."
                searchable
            />
            <native:text class="text-sm text-theme-on-surface">PHP accepted state: {{ $forcedQueue ?? '(empty)' }}</native:text>
        </native:column>

        <native:column class="w-full gap-2">
            <native:text class="text-sm font-semibold text-theme-on-surface">Validation and state</native:text>
            <native:text class="text-sm text-theme-on-surface">Static error presentation example; editing does not clear the message. Use Validation for real rules.</native:text>
            <firstlight:select
                :options="$priorityOptions"
                native:model="errorPriority"
                label="Required priority"
                placeholder="Select a priority"
                error="Choose a priority."
                required
            />
            <native:text class="text-sm text-theme-on-surface">PHP accepted state: {{ $errorPriority ?? '(empty)' }}</native:text>
            <firstlight:select
                :options="$priorityOptions"
                value="routine"
                label="Disabled priority"
                helper="Selection is unavailable."
                disabled
            />
            <firstlight:select
                ref="rejectedQueue"
                :options="['mine' => 'Mine', 'all' => 'All']"
                :value="$approvedQueue"
                label="Server-approved queue"
                helper="New proposals are deliberately rejected by PHP."
                @change="rejectQueue"
            />
            <native:text class="text-sm text-theme-on-surface">PHP accepted state: {{ $approvedQueue }}; Rejected attempts: {{ $rejectedAttempts }}</native:text>
        </native:column>
    </native:column>
</native:scroll-view>
