<native:column class="w-full flex-1 bg-theme-background">
    <firstlight:list ref="sampleList" :separator="$separators" :plain="$plain" class="w-full flex-1" @refresh="reloadItems" on-end-reached="loadMore">
        @if (! $empty)
        <firstlight:list-item
            headline="Account"
            supporting="Manage your profile and security"
            leading-icon="person"
            trailing-icon="chevron-right"
            trailing-icon-ios="chevron.right"
            trailing-icon-android="chevron_right"
            @press="recordAccount"
        />

        <firstlight:list-section header="Preferences" footer="Demo rows record presses; they do not open settings.">
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
        @if ($listItems !== [])
            <firstlight:list-section header="Paginated sample rows" footer="Tap a row to request deletion. All data is disposable.">
                @foreach ($listItems as $item)
                    <firstlight:list-item
                        :key="$item['id']"
                        :headline="$item['label']"
                        :supporting="$deletionAllowed ? 'Tap to delete this sample row' : 'Deletion denied by the sample Gate'"
                        :disabled="$this->denies('showcase-delete', $item)"
                        @press="requestDeletion({{ $item['id'] }})"
                    />
                @endforeach
            </firstlight:list-section>
        @endif
        @endif
    </firstlight:list>

    <native:column class="w-full gap-2 px-4 pt-2 pb-8">
        <native:text class="text-sm text-theme-on-surface">PHP: {{ $empty ? 'Empty list' : count($listItems).' rows' }} · {{ $listHasMore ? 'More available' : 'End of sample data' }} · refreshed {{ $refreshCount }}</native:text>
        <native:text class="text-sm text-theme-on-surface">{{ $pressCount }} presses · Last row: {{ $lastPressed }}</native:text>
        <native:row class="gap-3 flex-wrap">
            <native:button label="Load next page" variant="secondary" :disabled="! $listHasMore || $empty" @press="loadMore" />
            <native:button label="Test options" variant="secondary" @press="showTestOptions" />
        </native:row>
    </native:column>
    <firstlight:bottom-sheet ref="testOptions" :visible="$testOptionsVisible" a11y-label="List test options" @dismiss="closeTestOptions">
        <native:scroll-view class="w-full flex-1">
            <native:column class="w-full p-4 gap-3 pb-8">
                <native:text class="text-sm text-theme-on-surface">On-device checks: the preference rows only record presses. Pull to refresh; scroll sample rows to load 8 → 16 → 24. Delete a sample row, cancel then confirm; refresh must not restore it. Deny deletion to disable sample rows. Try plain, separators and empty states. Reset restores disposable sample data.</native:text>
                <native:toggle native:model="plain" label="Plain list" />
                <native:toggle native:model="separators" label="Separators" />
                <native:toggle native:model="empty" label="Empty list" />
                <native:toggle native:model="deletionAllowed" label="Allow sample deletion" />
                <native:text class="text-sm text-theme-on-surface">Recorded presses: {{ $pressCount }} · Last row: {{ $lastPressed }} · Refresh count: {{ $refreshCount }} · {{ count($deletedIds) }} deleted</native:text>
                <native:button label="Reset sample data" variant="secondary" @press="resetSampleData" />
                <native:button label="Return to list" @press="closeTestOptions" />
            </native:column>
        </native:scroll-view>
    </firstlight:bottom-sheet>
    <firstlight:confirmation-dialog
        ref="deleteSampleDialog"
        :visible="$confirmingListDestruction"
        :title="'Delete sample '.$pendingListDestructionKey.'?'"
        message="Only this demo's in-memory sample row will be removed. Reset sample data restores it."
        confirm-label="Delete sample"
        cancel-label="Keep sample"
        tone="destructive"
        @press="deleteSample"
        @dismiss="cancelDestructiveListAction"
    />
</native:column>
