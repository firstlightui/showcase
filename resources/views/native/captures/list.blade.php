<firstlight:list separator class="w-full flex-1 bg-theme-background">
    <firstlight:list-item
        headline="Account"
        supporting="Manage your profile and security"
        leading-icon="person"
        leading-icon-ios="person.crop.circle"
        leading-icon-android="account_circle"
        trailing-icon="chevron-right"
        trailing-icon-ios="chevron.right"
        trailing-icon-android="chevron_right"
        @press="capturePress"
    />

    <firstlight:list-section header="Preferences" footer="Signed in as Alex">
        <firstlight:list-item
            headline="Notifications"
            supporting="Mentions and status updates"
            leading-icon="notifications"
            trailing-icon="chevron-right"
            trailing-icon-ios="chevron.right"
            trailing-icon-android="chevron_right"
            @press="capturePress"
        />
        <firstlight:list-item
            headline="Privacy"
            supporting="Data export and retention"
            leading-icon="lock"
            trailing-icon="chevron-right"
            trailing-icon-ios="chevron.right"
            trailing-icon-android="chevron_right"
            @press="capturePress"
        />
    </firstlight:list-section>
</firstlight:list>
