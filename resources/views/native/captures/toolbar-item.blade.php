<native:column class="w-full flex-1 bg-theme-background">
    <native:scroll-view class="w-full flex-1">
        <native:column class="w-full p-5 gap-4">
            <native:text class="text-lg font-semibold text-theme-on-surface">Inbox</native:text>
            <native:text class="text-base text-theme-on-surface">Inbox shows a badge with 3 unread messages.</native:text>
            <native:text class="text-base text-theme-on-surface">Archive is disabled until a message is selected.</native:text>
            <native:text class="text-base text-theme-on-surface">More opens a menu of actions.</native:text>
            <native:text class="text-base text-theme-on-surface">New message is the separate primary action.</native:text>
        </native:column>
    </native:scroll-view>

    <firstlight:toolbar>
        <firstlight:toolbar-item icon="tray" icon-android="inbox" a11y-label="Inbox" :badge="3" @press="capturePress" />
        <firstlight:toolbar-item icon="archivebox" icon-android="archive" a11y-label="Archive" disabled @press="capturePress" />
        <firstlight:toolbar-item
            icon="ellipsis.circle"
            icon-android="more_horiz"
            a11y-label="More"
            :menu="[
                ['method' => 'capturePress', 'label' => 'Duplicate', 'icon' => 'plus.square.on.square', 'android' => 'content_copy'],
                ['method' => 'capturePress', 'label' => 'Delete', 'icon' => 'trash', 'android' => 'delete', 'role' => 'destructive'],
            ]"
        />
        <firstlight:toolbar-item primary icon="square.and.pencil" icon-android="edit_square" a11y-label="New message" @press="capturePress" />
    </firstlight:toolbar>
</native:column>
