<native:scroll-view class="w-full flex-1 bg-theme-background">
    <native:column class="w-full gap-6 pb-6">
        <firstlight:tabs native:model="tab" :tabs="$serverTabs" label="Server sections" />

        <firstlight:tabs native:model="siteTab" :tabs="$siteTabs" label="Site sections" />
    </native:column>
</native:scroll-view>
