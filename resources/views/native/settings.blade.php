<native:list class="w-full flex-1 bg-theme-background" separator>
    <native:list-section header="Notifications" footer="Sample preferences saved on this device. These controls do not register push notifications.">
        <native:column class="w-full px-4 py-3 bg-theme-surface">
            <firstlight:switch label="Push notifications" native:model="notifications" />
        </native:column>
        <native:column class="w-full px-4 py-3 bg-theme-surface">
            <firstlight:switch label="Product news" native:model="marketing" />
        </native:column>
    </native:list-section>

    <native:list-section header="Email" footer="Sample preference only. The showcase does not send email.">
        <native:column class="w-full px-4 py-3 bg-theme-surface">
            <firstlight:select label="Digest" :options="$digestOptions" native:model="digest" />
        </native:column>
    </native:list-section>

    <native:list-section header="Account">
        <native:column class="w-full bg-theme-surface">
            <firstlight:list-item
                headline="Privacy"
                trailing-icon="chevron-right"
                trailing-icon-ios="chevron.right"
                trailing-icon-android="chevron_right"
                @press="openPrivacy"
            />
        </native:column>
    </native:list-section>
</native:list>
