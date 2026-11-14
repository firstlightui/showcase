<native:column class="w-full flex-1 bg-theme-background">
    <native:scroll-view class="w-full flex-1">
        <native:column class="w-full p-4 pb-32 gap-5">
            <native:text class="text-sm font-semibold text-theme-on-surface">On-device checks</native:text>
            <native:text class="text-sm text-theme-on-surface-variant">The bottom toolbar holds four actions and a separate New note action. Each records its name below; none opens a picker or changes the note. On Android the toolbar hides while the page scrolls down and returns when it scrolls up.</native:text>

            <native:column class="w-full gap-3">
                <native:text class="text-sm font-semibold text-theme-on-surface">Result</native:text>
                <native:text class="text-sm text-theme-on-surface">Last action: {{ $lastAction }}</native:text>
                <native:text class="text-sm text-theme-on-surface">Recorded actions: {{ $actionCount }}</native:text>
            </native:column>

            <native:column class="w-full gap-3">
                <native:text class="text-sm font-semibold text-theme-on-surface">Badge and disabled state</native:text>
                <native:text class="text-sm text-theme-on-surface-variant">Comments shows a badge with the unread count, and opening it clears the count. Turning Markup off disables that action in place.</native:text>
                <firstlight:button variant="secondary" @press="addComment">Add a comment</firstlight:button>
                <native:text class="text-sm text-theme-on-surface">Unread comments: {{ $comments }}</native:text>
                <firstlight:switch ref="markupAvailable" native:model="markupAvailable" label="Markup available" />
            </native:column>

            <native:column class="w-full gap-3">
                <native:text class="text-sm font-semibold text-theme-on-surface">Sample note</native:text>
                @foreach ([
                    'Pack the tent, two sleeping bags and the camp stove.',
                    'Check the forecast on Thursday evening before leaving.',
                    'Fill both water containers and bring the filter.',
                    'Leave the trail plan with someone at home.',
                    'Charge the head torches and the spare battery.',
                    'Buy matches, firelighters and a lighter.',
                    'Pack the first aid kit and sunscreen.',
                    'Bring the paper map as well as the phone.',
                    'Print the campsite booking.',
                    'Return the borrowed cooler on Monday.',
                ] as $line)
                    <native:text class="text-base text-theme-on-surface">{{ $line }}</native:text>
                @endforeach
            </native:column>
        </native:column>
    </native:scroll-view>

    <firstlight:toolbar>
        <firstlight:toolbar-item icon="checklist" a11y-label="Checklist" @press="record('Checklist')" />
        <firstlight:toolbar-item
            icon="paperclip"
            icon-android="attach_file"
            a11y-label="Attach"
            :menu="[
                ['method' => 'attachPhoto', 'label' => 'Photo', 'icon' => 'photo', 'android' => 'image'],
                ['method' => 'attachFile', 'label' => 'File', 'icon' => 'doc', 'android' => 'description'],
                ['method' => 'scanDocument', 'label' => 'Scan document', 'icon' => 'doc.viewfinder', 'android' => 'document_scanner', 'disabled' => true],
            ]"
        />
        <firstlight:toolbar-item
            icon="pencil.tip.crop.circle"
            icon-android="draw"
            a11y-label="Markup"
            :disabled="! $markupAvailable"
            @press="record('Markup')"
        />
        <firstlight:toolbar-item
            icon="bubble.left"
            icon-android="chat_bubble"
            a11y-label="Comments"
            :badge="$comments"
            @press="openComments"
        />
        <firstlight:toolbar-item
            primary
            icon="square.and.pencil"
            icon-android="edit_square"
            a11y-label="New note"
            haptic="keypress"
            @press="record('New note')"
        />
    </firstlight:toolbar>
</native:column>
