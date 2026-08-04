<native:scroll-view class="w-full flex-1 bg-theme-background">
    <native:column class="w-full p-4 gap-5">
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
            <firstlight:select
                ref="triageLevel"
                :options="$triageOptions"
                native:model="triageLevel"
                label="Triage level"
                helper="Integer identity is preserved through the callback."
                required
            />
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
            <firstlight:select
                ref="forcedQueue"
                :options="['mine' => 'Mine', 'all' => 'All']"
                native:model="forcedQueue"
                label="Forced searchable queue"
                helper="Search is explicitly enabled for this small set."
                searchable
            />
        </native:column>

        <native:column class="w-full gap-2">
            <native:text class="text-sm font-semibold text-theme-on-surface">Validation and state</native:text>
            <firstlight:select
                :options="$priorityOptions"
                label="Required priority"
                placeholder="Select a priority"
                error="Choose a priority."
                required
            />
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
        </native:column>
    </native:column>
</native:scroll-view>
