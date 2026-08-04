<native:scroll-view class="w-full flex-1 bg-theme-background">
    <native:column class="w-full p-5 gap-6">
        <firstlight:switch
            native:model="notifications"
            label="Notifications"
            helper="Receive account updates."
            a11y-hint="Controls notification delivery"
        />

        <firstlight:switch
            native:model="automaticUpdates"
            label="Automatic updates"
            helper="Install updates automatically."
            a11y-hint="Controls automatic updates"
        />

        <firstlight:switch
            native:model="settingWithError"
            label="Setting with error"
            error="Choose whether this setting is enabled."
            a11y-hint="Resolve the setting error"
        />
    </native:column>
</native:scroll-view>
