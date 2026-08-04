<native:scroll-view class="w-full flex-1 bg-theme-background">
    <native:column class="w-full p-5 gap-5">
        <native:text class="text-lg font-semibold text-theme-on-surface">Action buttons</native:text>
        <firstlight:button label="Primary action" />
        <firstlight:button label="Secondary action" variant="secondary" />
        <firstlight:button label="Delete draft" variant="destructive" />
        <firstlight:button label="Confirm selection" variant="success" />
        <firstlight:button label="Ghost action" variant="ghost" />
        <firstlight:button label="Add item" icon="plus" />
        <firstlight:button label="Continue" icon-trailing="chevron-right" />
        <firstlight:button label="Unavailable" disabled />
        <firstlight:button label="Saving changes" loading a11y-hint="Saving is in progress" />
    </native:column>
</native:scroll-view>
