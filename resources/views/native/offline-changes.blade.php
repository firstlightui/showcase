<native:list class="w-full flex-1 bg-theme-background" separator>
    <native:list-section header="Tasks" footer="Tap a task to tick it. While offline the change waits to sync, and the nav bar says how many are waiting.">
        @foreach ($tasks as $id => $task)
            @if ($task['state'] === 'waiting')
                <native:column class="w-full bg-theme-surface">
                    <firstlight:list-item
                        :headline="$task['title']"
                        :leading-icon="$task['done'] ? 'check_circle' : 'radio_button_unchecked'"
                        :leading-icon-ios="$task['done'] ? 'checkmark.circle.fill' : 'circle'"
                        :leading-icon-android="$task['done'] ? 'check_circle' : 'radio_button_unchecked'"
                        :leading-icon-tone="$task['done'] ? 'success' : 'neutral'"
                        :trailing-status="__('firstlight::chrome.sync_waiting_label')"
                        trailing-status-tone="neutral"
                        state="provisional"
                        :state-label="__('firstlight::chrome.sync_waiting_label')"
                        @press="toggle({{ $id }})"
                    />
                </native:column>
            @elseif ($task['state'] === 'failed')
                <native:column class="w-full bg-theme-surface">
                    <firstlight:list-item
                        :headline="$task['title']"
                        :leading-icon="$task['done'] ? 'check_circle' : 'radio_button_unchecked'"
                        :leading-icon-ios="$task['done'] ? 'checkmark.circle.fill' : 'circle'"
                        :leading-icon-android="$task['done'] ? 'check_circle' : 'radio_button_unchecked'"
                        :leading-icon-tone="$task['done'] ? 'success' : 'neutral'"
                        :trailing-status="__('firstlight::chrome.sync_failed_label')"
                        trailing-status-tone="danger"
                        @press="toggle({{ $id }})"
                    />
                </native:column>
            @else
                <native:column class="w-full bg-theme-surface">
                    <firstlight:list-item
                        :headline="$task['title']"
                        :leading-icon="$task['done'] ? 'check_circle' : 'radio_button_unchecked'"
                        :leading-icon-ios="$task['done'] ? 'checkmark.circle.fill' : 'circle'"
                        :leading-icon-android="$task['done'] ? 'check_circle' : 'radio_button_unchecked'"
                        :leading-icon-tone="$task['done'] ? 'success' : 'neutral'"
                        @press="toggle({{ $id }})"
                    />
                </native:column>
            @endif
        @endforeach
    </native:list-section>

    <native:list-section header="Try the states" footer="Turn on airplane mode and tick some tasks. When you're back online, the changes sync next time a screen opens or you return to the app.">
        <native:column class="w-full px-4 py-3 bg-theme-surface">
            <firstlight:switch label="Fail the next sync" native:model="failNextSync" />
        </native:column>
        <native:column class="w-full px-4 py-3 gap-3 bg-theme-surface">
            @if ($hasQueued && ! $syncing && ! $offline)
                <firstlight:button label="Sync now" @press="syncNow" />
            @endif
            <firstlight:button label="Start over" variant="secondary" @press="startOver" />
        </native:column>
    </native:list-section>
</native:list>
