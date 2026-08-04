<native:scroll-view class="w-full flex-1 bg-theme-background">
    <native:column class="w-full p-5 gap-6">
        <firstlight:choice-group
            :options="$priorityOptions"
            native:model="priority"
            label="Priority"
            helper="Choose one priority."
            a11y-hint="Select one priority"
            required
        />

        <firstlight:choice-group
            :options="$notificationOptions"
            native:model="notifications"
            label="Notifications"
            helper="Choose any that apply."
            multiple
        />

        <firstlight:choice-group
            :options="$priorityOptions"
            :value="null"
            label="Approval"
            error="Choose an approval priority."
            required
        />
    </native:column>
</native:scroll-view>
