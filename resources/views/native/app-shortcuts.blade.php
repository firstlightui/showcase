<firstlight:list class="w-full flex-1 bg-theme-background">
    <firstlight:list-section header="Built into the app" footer="On iOS, say a phrase to Siri, search Spotlight, or add these in the Shortcuts app. On Android, long-press the app icon; Android has no voice for shortcuts. Tap a row to open its screen here.">
        @foreach ($shortcuts as $shortcut)
            <firstlight:list-item
                :headline="$shortcut['title']"
                :supporting="$shortcut['places']"
                leading-icon="shortcut"
                leading-icon-ios="arrow.up.forward.app"
                leading-icon-android="shortcut"
                leading-icon-tone="info"
                trailing-icon="chevron-right"
                trailing-icon-ios="chevron.right"
                trailing-icon-android="chevron_right"
                a11y-hint="Opens the screen this shortcut opens"
                @press="open('{{ $shortcut['id'] }}')"
            />
        @endforeach
    </firstlight:list-section>

    <firstlight:list-section header="Actions that run PHP" footer="These run a PHP class instead of opening a screen. On iOS, Siri and Spotlight answer without opening the app, and a closed app runs the action when it next opens. On Android, the app icon's menu runs it in the background when the app is running. Tap one to run it here.">
        @foreach ($actions as $action)
            <firstlight:list-item
                :headline="$action['title']"
                :supporting="$action['places']"
                leading-icon="bolt"
                leading-icon-ios="bolt.fill"
                leading-icon-android="bolt"
                leading-icon-tone="warning"
                a11y-hint="Runs this action here, as the shortcut would"
                @press="runAction('{{ $action['id'] }}')"
            />
        @endforeach
        <firstlight:list-item
            headline="Open the newest quick post"
            :supporting="$quickPosts"
            leading-icon="article"
            leading-icon-ios="doc.text"
            leading-icon-android="article"
            leading-icon-tone="info"
            trailing-icon="chevron-right"
            trailing-icon-ios="chevron.right"
            trailing-icon-android="chevron_right"
            a11y-hint="Opens the newest post a shortcut added"
            @press="openNewestQuickPost"
        />
    </firstlight:list-section>

    <firstlight:list-section header="Added while the app runs" footer="AppShortcuts::push() adds to the app icon's long-press menu. Its title shows on a locked phone, so real apps keep these free of personal details.">
        <firstlight:list-item
            headline="Add the newest post"
            supporting="Puts the newest post first in the long-press menu"
            leading-icon="add"
            leading-icon-ios="plus.app"
            leading-icon-android="add_box"
            leading-icon-tone="success"
            a11y-hint="Adds a shortcut to the newest post"
            @press="addNewestPost"
        />
        <firstlight:list-item
            headline="Remove it"
            supporting="A copy pinned to the Android home screen stays"
            leading-icon="remove"
            leading-icon-ios="minus.square"
            leading-icon-android="indeterminate_check_box"
            leading-icon-tone="danger"
            a11y-hint="Removes the newest post shortcut"
            @press="removeNewestPost"
        />
    </firstlight:list-section>
</firstlight:list>
