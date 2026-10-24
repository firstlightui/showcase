<native:list class="w-full flex-1">
    <native:list-section header="Notifications" footer="Alerts for replies and mentions.">
        <firstlight:switch label="Push notifications" native:model="notifications" />
        <firstlight:switch label="Product news" native:model="marketing" />
    </native:list-section>

    <native:list-section header="Email" footer="A summary of activity you missed.">
        <firstlight:select label="Digest" :options="$digestOptions" native:model="digest" />
    </native:list-section>

    <native:list-section header="Account">
        <firstlight:list-item
            headline="Privacy"
            trailing-icon="chevron-right"
            trailing-icon-ios="chevron.right"
            trailing-icon-android="chevron_right"
            @press="openPrivacy"
        />
    </native:list-section>
</native:list>
