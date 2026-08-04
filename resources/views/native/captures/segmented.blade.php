<native:scroll-view class="w-full flex-1 bg-theme-background">
    <native:column class="w-full p-5 gap-6">
        <firstlight:segmented
            :options="$queueOptions"
            native:model="queue"
            label="Queue"
            helper="Choose the active queue."
            a11y-hint="Changes the active queue"
        />

        <firstlight:segmented
            :options="$priorityOptions"
            native:model="priority"
            label="Priority"
            helper="Routine is selected; Urgent is unavailable."
            a11y-hint="Urgent is unavailable"
        />

        <firstlight:segmented
            :options="$queueOptions"
            native:model="errorQueue"
            label="Queue with error"
            error="Choose Mine before continuing."
            a11y-hint="Resolve the queue error"
        />
    </native:column>
</native:scroll-view>
