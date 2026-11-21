<native:scroll-view class="w-full flex-1 bg-theme-background">
    <native:column class="w-full p-4 pb-8 gap-4">
        <native:text class="text-sm text-theme-on-surface-variant">This app's tab bar is yours to arrange. Home stays first; the other tabs can move between the bar and More. Changes apply when you tap Done.</native:text>
        <native:button label="Edit tabs" variant="primary" @press="editTabs" />
    </native:column>
</native:scroll-view>
