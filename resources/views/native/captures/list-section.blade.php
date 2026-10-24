<firstlight:list separator class="w-full flex-1 bg-theme-background">
    <firstlight:list-section header="Account" footer="Signed in as Alex">
        <firstlight:list-item
            headline="Profile"
            supporting="Name, photo and contact details"
            leading-icon="person"
            trailing-icon="chevron-right"
            trailing-icon-ios="chevron.right"
            trailing-icon-android="chevron_right"
            @press="capturePress"
        />
        <firstlight:list-item
            headline="Security"
            supporting="Password and two-factor sign-in"
            leading-icon="lock"
            trailing-icon="chevron-right"
            trailing-icon-ios="chevron.right"
            trailing-icon-android="chevron_right"
            @press="capturePress"
        />
    </firstlight:list-section>

    <firstlight:list-section header="Notifications">
        <firstlight:list-item
            headline="Mentions"
            supporting="When someone mentions you"
            leading-icon="notifications"
            @press="capturePress"
        />
    </firstlight:list-section>

    <firstlight:list-section footer="Exports include every record you can access.">
        <firstlight:list-item
            headline="Export data"
            leading-icon="share"
            @press="capturePress"
        />
    </firstlight:list-section>
</firstlight:list>
