<firstlight:list class="w-full flex-1 bg-theme-background">
    <firstlight:list-section header="Lists" footer="Thirty sample posts and 5,000 contacts live in this app's SQLite database.">
        <firstlight:list-item
            headline="Posts"
            supporting="Search, pull to refresh, load more and swipe to delete"
            leading-icon="search"
            leading-icon-ios="magnifyingglass"
            leading-icon-android="search"
            leading-icon-tone="info"
            trailing-icon="chevron-right"
            trailing-icon-ios="chevron.right"
            trailing-icon-android="chevron_right"
            a11y-hint="Opens the searchable list recipe"
            @press="openPosts"
        />
        <firstlight:list-item
            headline="Post detail"
            supporting="Route-model binding opens the newest post"
            leading-icon="article"
            leading-icon-ios="doc.text.fill"
            leading-icon-android="article"
            leading-icon-tone="success"
            trailing-icon="chevron-right"
            trailing-icon-ios="chevron.right"
            trailing-icon-android="chevron_right"
            a11y-hint="Opens the list and detail recipe"
            @press="openPostDetail"
        />
        <firstlight:list-item
            headline="Contacts"
            supporting="5,000 rows in a virtual list that builds only what is visible"
            leading-icon="contacts"
            leading-icon-ios="person.2.fill"
            leading-icon-android="contacts"
            leading-icon-tone="info"
            trailing-icon="chevron-right"
            trailing-icon-ios="chevron.right"
            trailing-icon-android="chevron_right"
            a11y-hint="Opens the large list recipe"
            @press="openContacts"
        />
    </firstlight:list-section>

    <firstlight:list-section header="Forms" footer="Every recipe is the code from a Firstlight how-to guide.">
        <firstlight:list-item
            headline="New post"
            supporting="Form Request rules, field and form errors, loading Save"
            leading-icon="edit"
            leading-icon-ios="square.and.pencil"
            leading-icon-android="edit"
            leading-icon-tone="warning"
            trailing-icon="chevron-right"
            trailing-icon-ios="chevron.right"
            trailing-icon-android="chevron_right"
            a11y-hint="Opens the form screen recipe"
            @press="openNewPost"
        />
        <firstlight:list-item
            headline="Settings"
            supporting="Grouped switches and a select that save as you change them"
            leading-icon="settings"
            leading-icon-ios="gearshape.fill"
            leading-icon-android="settings"
            leading-icon-tone="neutral"
            trailing-icon="chevron-right"
            trailing-icon-ios="chevron.right"
            trailing-icon-android="chevron_right"
            a11y-hint="Opens the settings screen recipe"
            @press="openSettings"
        />
        <firstlight:list-item
            headline="Cover photo"
            supporting="Crop, validate and save a photo to the newest post"
            leading-icon="photo"
            leading-icon-ios="photo.fill"
            leading-icon-android="photo"
            leading-icon-tone="success"
            trailing-icon="chevron-right"
            trailing-icon-ios="chevron.right"
            trailing-icon-android="chevron_right"
            a11y-hint="Opens the photo upload recipe"
            @press="openPostCover"
        />
    </firstlight:list-section>
    <firstlight:list-section header="Drafts" footer="Edit the post, go back without saving, then open it again. Change it on another device first to see a draft of an older version.">
        <firstlight:list-item
            headline="Edit post"
            supporting="Unsaved edits survive Back and the edge swipe"
            leading-icon="edit-note"
            leading-icon-ios="pencil.and.list.clipboard"
            leading-icon-android="edit_note"
            leading-icon-tone="info"
            trailing-icon="chevron-right"
            trailing-icon-ios="chevron.right"
            trailing-icon-android="chevron_right"
            a11y-hint="Opens the newest post for editing"
            @press="openEditPost"
        />
        <firstlight:list-item
            headline="Change it on another device"
            supporting="Updates the newest post so a kept draft is out of date"
            leading-icon="sync"
            leading-icon-ios="arrow.triangle.2.circlepath"
            leading-icon-android="sync"
            leading-icon-tone="warning"
            a11y-hint="Changes the newest post's body"
            @press="changePostElsewhere"
        />
    </firstlight:list-section>
    <firstlight:list-section header="Offline and data freshness" footer="Turn on airplane mode to see the offline notices and the Offline line under the screen title.">
        <firstlight:list-item
            headline="Server status"
            supporting="Says how old cached data is, offline, and when a refresh fails"
            leading-icon="cloud-sync"
            leading-icon-ios="clock.arrow.circlepath"
            leading-icon-android="cloud_sync"
            leading-icon-tone="info"
            trailing-icon="chevron-right"
            trailing-icon-ios="chevron.right"
            trailing-icon-android="chevron_right"
            a11y-hint="Opens a cached server list"
            @press="openServerStatus"
        />
        <firstlight:list-item
            headline="Offline changes"
            supporting="Queues changes while offline and syncs them when you're back"
            leading-icon="cloud-off"
            leading-icon-ios="icloud.slash"
            leading-icon-android="cloud_off"
            leading-icon-tone="warning"
            trailing-icon="chevron-right"
            trailing-icon-ios="chevron.right"
            trailing-icon-android="chevron_right"
            a11y-hint="Opens a task list that works offline"
            @press="openOfflineChanges"
        />
    </firstlight:list-section>
    <firstlight:list-section header="Security" footer="Locks after a minute in the background. Unlocking needs the paid biometrics plugin.">
        <firstlight:list-item
            headline="App lock"
            supporting="Hide content in the app switcher and unlock with Face ID or a fingerprint"
            leading-icon="lock"
            leading-icon-ios="lock.fill"
            leading-icon-android="lock"
            leading-icon-tone="danger"
            trailing-icon="chevron-right"
            trailing-icon-ios="chevron.right"
            trailing-icon-android="chevron_right"
            a11y-hint="Opens the app lock recipe"
            @press="openVault"
        />
    </firstlight:list-section>
    <firstlight:list-section header="System" footer="Shortcuts appear after a native build. The vault shortcut shows the lock screen first when the app is locked.">
        <firstlight:list-item
            headline="App shortcuts"
            supporting="Open screens from Siri, Spotlight and the app icon"
            leading-icon="shortcut"
            leading-icon-ios="arrow.up.forward.app"
            leading-icon-android="shortcut"
            leading-icon-tone="info"
            trailing-icon="chevron-right"
            trailing-icon-ios="chevron.right"
            trailing-icon-android="chevron_right"
            a11y-hint="Opens the app shortcuts recipe"
            @press="openShortcuts"
        />
    </firstlight:list-section>
</firstlight:list>
