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
</firstlight:list>
