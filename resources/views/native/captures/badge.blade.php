<native:scroll-view class="w-full flex-1 bg-theme-background">
    <native:column class="w-full p-5 gap-5">
        <native:text class="text-lg font-semibold text-theme-on-surface">Badge markers</native:text>
        <firstlight:badge :count="1" a11y-label="1 unread item" />
        <firstlight:badge :count="9" tone="info" a11y-label="9 informational items" />
        <firstlight:badge label="Ready" tone="success" />
        <firstlight:badge :count="99" tone="warning" a11y-label="99 items awaiting review" />
        <firstlight:badge :count="100" tone="danger" a11y-label="100 blocked items" />
    </native:column>
</native:scroll-view>
