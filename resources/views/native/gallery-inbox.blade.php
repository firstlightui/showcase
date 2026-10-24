<native:column class="w-full flex-1 bg-theme-background">
    <native:column class="w-full px-4 pt-2 pb-3 gap-3">
        <firstlight:segmented
            native:model="filter"
            :options="$filterOptions"
            a11y-label="Show messages"
        />
        <native:row class="w-full gap-2 items-center">
            @if ($unread > 0)
                <firstlight:status-label
                    :label="$unread.' unread'"
                    tone="info"
                    icon="mail"
                    icon-ios="envelope.badge.fill"
                    icon-android="mark_email_unread"
                />
            @else
                <firstlight:status-label
                    label="All caught up"
                    tone="success"
                    icon="check"
                    icon-ios="checkmark.circle.fill"
                    icon-android="check_circle"
                />
            @endif
            @if ($flagged > 0)
                <firstlight:status-label
                    :label="$flagged.' flagged'"
                    tone="warning"
                    icon="flag"
                    icon-ios="flag.fill"
                    icon-android="flag"
                />
            @endif
        </native:row>
    </native:column>

    @if ($sections === [])
        <firstlight:callout
            :message="$emptyMessage"
            tone="success"
            class="mx-4"
        />
    @else
        <firstlight:list class="w-full flex-1 bg-theme-background">
            @foreach ($sections as $section => $rows)
                <firstlight:list-section :header="$section">
                    @foreach ($rows as $message)
                        @if ($message['icon'] !== null)
                            <firstlight:list-item
                                :headline="$message['from']"
                                :supporting="$message['subject']"
                                :leading-monogram="$message['initials']"
                                :trailing-icon="$message['icon'][0]"
                                :trailing-icon-ios="$message['icon'][1]"
                                :trailing-icon-android="$message['icon'][2]"
                                :trailing-icon-tone="$message['icon'][3]"
                                :a11y-label="$message['a11yLabel']"
                                a11y-hint="Marks the message as read"
                                :leading-actions="$message['leadingActions']"
                                :trailing-actions="$message['trailingActions']"
                                :context-menu="$message['contextMenu']"
                                @press="open({{ $message['id'] }})"
                            />
                        @else
                            <firstlight:list-item
                                :headline="$message['from']"
                                :supporting="$message['subject']"
                                :leading-monogram="$message['initials']"
                                :trailing-text="$message['time']"
                                :a11y-label="$message['a11yLabel']"
                                :leading-actions="$message['leadingActions']"
                                :trailing-actions="$message['trailingActions']"
                                :context-menu="$message['contextMenu']"
                                @press="open({{ $message['id'] }})"
                            />
                        @endif
                    @endforeach
                </firstlight:list-section>
            @endforeach
        </firstlight:list>
    @endif

    <firstlight:confirmation-dialog
        :visible="$confirmingDelete !== null"
        title="Delete message?"
        :message="$pendingSubject === '' ? 'This message will be removed.' : '“'.$pendingSubject.'” will be removed. Archive keeps a copy.'"
        confirm-label="Delete"
        cancel-label="Keep message"
        tone="destructive"
        @press="deleteMessage"
        @dismiss="cancelDelete"
    />
</native:column>
