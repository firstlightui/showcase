<native:scroll-view class="w-full flex-1 bg-theme-background">
    <native:column class="w-full p-4 gap-5">
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
                a11y-hint="Opens notification preferences"
                @press="recordAccount"
            />
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
