<native:column class="w-full flex-1 bg-theme-background">
    <firstlight:list separator class="w-full flex-1" @refresh="reloadItems">
        <firstlight:list-item
            headline="Account"
            supporting="Manage your profile and security"
            leading-icon="person"
            trailing-icon="chevron-right"
            trailing-icon-ios="chevron.right"
            trailing-icon-android="chevron_right"
            @press="recordAccount"
        />

        <firstlight:list-section header="Preferences" footer="Changes sync automatically">
            <firstlight:list-item
                headline="Notifications"
                supporting="Mentions and status updates"
                leading-icon="notifications"
                trailing-icon="chevron-right"
                trailing-icon-ios="chevron.right"
                trailing-icon-android="chevron_right"
                @press="recordNotifications"
            />
            <firstlight:list-item
                headline="Privacy"
                supporting="Data export and retention"
                leading-icon="lock"
                trailing-icon="chevron-right"
                trailing-icon-ios="chevron.right"
                trailing-icon-android="chevron_right"
                @press="recordPrivacy"
            />
        </firstlight:list-section>
    </firstlight:list>

    <native:column class="w-full gap-2 px-4 py-4">
        <native:text class="text-sm font-semibold text-theme-on-surface">Interaction</native:text>
        <native:text class="text-sm text-theme-on-surface">Recorded presses: {{ $pressCount }}</native:text>
        <native:text class="text-sm text-theme-on-surface">Last row: {{ $lastPressed }}</native:text>
        <native:text class="text-sm text-theme-on-surface">Refresh count: {{ $refreshCount }}</native:text>
    </native:column>
</native:column>
