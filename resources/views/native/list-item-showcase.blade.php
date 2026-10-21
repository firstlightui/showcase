<native:scroll-view class="w-full flex-1 bg-theme-background">
    <native:column class="w-full p-4 pb-24 gap-5">
        <native:text class="text-sm font-semibold text-theme-on-surface">On-device checks</native:text>
        <native:text class="text-sm text-theme-on-surface-variant">Tap each row: Recorded presses increases and Last row names it. Notifications records only; no navigation. Managed account should not change the result.</native:text>
        <native:column class="w-full gap-3">
            <native:text class="text-sm font-semibold text-theme-on-surface">Content</native:text>
            <firstlight:list-item headline="Account" @press="recordAccount" />
            <firstlight:list-item
                headline="Billing"
                supporting="Invoices and payment methods"
                trailing-text="Open"
                @press="recordBilling"
            />
        </native:column>

        <native:column class="w-full gap-3">
            <native:text class="text-sm font-semibold text-theme-on-surface">Leading identity</native:text>
            <firstlight:list-item
                headline="Profile"
                supporting="Personal details and security"
                leading-avatar="https://placehold.co/80x80/png?text=AJ"
                trailing-icon="chevron-right"
                trailing-icon-ios="chevron.right"
                trailing-icon-android="chevron_right"
                @press="recordProfile"
            />
            <firstlight:list-item
                headline="Team"
                supporting="12 active members"
                leading-monogram="FL"
                trailing-text="Admin"
                @press="recordTeam"
            />
        </native:column>

        <native:column class="w-full gap-3">
            <native:text class="text-sm font-semibold text-theme-on-surface">Platform icons</native:text>
            <firstlight:list-item
                headline="Notifications"
                supporting="Mentions and status updates"
                leading-icon="notifications"
                leading-icon-ios="bell.fill"
                leading-icon-android="notifications"
                trailing-icon="chevron-right"
                trailing-icon-ios="chevron.right"
                trailing-icon-android="chevron_right"
                a11y-hint="Records a Notifications press; does not navigate"
                @press="recordNotifications"
            />
        </native:column>

        <native:column class="w-full gap-3">
            <native:text class="text-sm font-semibold text-theme-on-surface">Swipe actions</native:text>
            <native:text class="text-sm text-theme-on-surface-variant">Swipe either way, then tap an action. Long swipes never execute. Delete records only: the row stays visible. Toggle disabled while a drawer is open to check reconciliation.</native:text>
            <firstlight:button label="Toggle swipe rows" @press="toggleSwipeRows" />
            <firstlight:list plain separator class="w-full h-80">
                <firstlight:list-item
                    headline="Message"
                    supporting="Archive, flag, or request deletion"
                    :disabled="$swipeRowsDisabled"
                    :leading-actions="[
                        ['method' => 'recordSwipe(\'Archive\',42)', 'label' => 'Archive', 'icon' => 'archive', 'ios' => 'archivebox'],
                    ]"
                    :trailing-actions="[
                        ['method' => 'recordSwipe(\'Delete\',42)', 'label' => 'Delete', 'icon' => 'delete', 'ios' => 'trash', 'role' => 'destructive'],
                        ['method' => 'recordSwipe(\'Flag\',42)', 'label' => 'Flag', 'icon' => 'flag'],
                    ]"
                    @press="recordMessage"
                />
                <firstlight:list-section header="Grouped rows">
                    <firstlight:list-item
                        headline="Grouped message"
                        supporting="Actions also work inside a section"
                        :disabled="$swipeRowsDisabled"
                        :trailing-actions="[
                            ['method' => 'recordSwipe(\'Flag\',73)', 'label' => 'Flag', 'icon' => 'flag'],
                        ]"
                        @press="recordMessage"
                    />
                    <firstlight:list-item
                        headline="Disabled message"
                        supporting="Neither edge nor accessibility actions can activate"
                        disabled
                        :trailing-actions="[
                            ['method' => 'recordSwipe(\'Delete\',99)', 'label' => 'Delete', 'role' => 'destructive'],
                        ]"
                        @press="recordMessage"
                    />
                </firstlight:list-section>
            </firstlight:list>
            <native:text class="text-sm text-theme-on-surface">Recorded swipe actions: {{ $swipeCount }}</native:text>
            <native:text class="text-sm text-theme-on-surface">Last swipe action: {{ $lastSwipe }}</native:text>
        </native:column>

        <native:column class="w-full gap-3">
            <native:text class="text-sm font-semibold text-theme-on-surface">Disabled</native:text>
            <firstlight:list-item
                headline="Managed account"
                supporting="Only an administrator can make changes"
                leading-icon="lock"
                trailing-text="Unavailable"
                disabled
                @press="recordAccount"
            />
        </native:column>

        <native:column class="w-full gap-2 pb-6">
            <native:text class="text-sm font-semibold text-theme-on-surface">Interaction</native:text>
            <native:text class="text-sm text-theme-on-surface">Recorded presses: {{ $pressCount }}</native:text>
            <native:text class="text-sm text-theme-on-surface">Last row: {{ $lastPressed }}</native:text>
        </native:column>
    </native:column>
</native:scroll-view>
