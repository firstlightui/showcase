<native:scroll-view class="w-full flex-1 bg-theme-background">
    <native:column class="w-full py-3">
        <firstlight:list-item
            headline="Account"
            supporting="Manage your profile and security"
            leading-icon="person"
            leading-icon-ios="person.crop.circle"
            leading-icon-android="account_circle"
            trailing-icon="chevron-right"
            trailing-icon-ios="chevron.right"
            trailing-icon-android="chevron_right"
            a11y-hint="Opens account settings"
            @press="capturePress"
        />
        <firstlight:list-item
            headline="Wojt Janowski"
            supporting="Owner"
            leading-monogram="WJ"
            trailing-text="Admin"
            @press="capturePress"
        />
        <firstlight:list-item
            headline="Billing"
            supporting="Invoices and payment methods"
            trailing-text="Open"
            @press="capturePress"
        />
        <firstlight:list-item
            headline="Unavailable account"
            supporting="Ask an administrator for access"
            leading-icon="lock"
            trailing-text="Unavailable"
            disabled
            @press="capturePress"
        />
    </native:column>
</native:scroll-view>
