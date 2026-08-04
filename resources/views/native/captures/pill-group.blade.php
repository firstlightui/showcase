<native:scroll-view class="w-full flex-1 bg-theme-background">
    <native:column class="w-full p-5 gap-6">
        <firstlight:pill-group
            :options="$queueOptions"
            native:model="queue"
            label="Queue"
            helper="Choose zero or one queue."
            a11y-hint="Tap the selected queue to clear it"
        />

        <firstlight:pill-group
            :options="$queueOptions"
            native:model="queues"
            label="Queues"
            helper="Choose any that apply."
            multiple
            required
        />

        <firstlight:pill-group
            :options="$reviewOptions"
            :value="null"
            label="Workflow status"
            error="Choose a workflow status."
            a11y-hint="Ready for assignment is unavailable"
        />
    </native:column>
</native:scroll-view>
