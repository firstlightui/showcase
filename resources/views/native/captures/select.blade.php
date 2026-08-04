<native:scroll-view class="w-full flex-1 bg-theme-background">
    <native:column class="w-full p-5 gap-6">
        <firstlight:select
            :options="$priorityOptions"
            native:model="priority"
            label="Priority"
            placeholder="Select a priority"
            helper="A nullable stable string value."
        />

        <firstlight:select
            :options="[10 => 'Low', 20 => 'Medium', 30 => 'High']"
            :value="20"
            label="Triage level"
            required
        />

        <firstlight:select
            :options="$largeQueueOptions"
            value="queue-4"
            label="Large queue"
            helper="Search is automatic for thirteen options."
        />

        <firstlight:select
            :options="$priorityOptions"
            label="Required priority"
            placeholder="Select a priority"
            error="Choose a priority."
            required
        />
    </native:column>
</native:scroll-view>
