<native:scroll-view class="w-full flex-1 bg-theme-background">
    <native:column class="w-full p-5 gap-5">
        <native:text class="text-lg font-semibold text-theme-on-surface">Workflow status</native:text>
        <firstlight:status-label label="Draft" />
        <firstlight:status-label label="In progress" tone="info" icon="info" icon-ios="info.circle" icon-android="info" />
        <firstlight:status-label label="Ready" tone="success" icon="check_circle" icon-ios="checkmark.circle" icon-android="check_circle" />
        <firstlight:status-label label="Awaiting review" tone="warning" icon="warning" icon-ios="exclamationmark.triangle" icon-android="warning" />
        <firstlight:status-label label="Blocked" tone="danger" icon="error" icon-ios="xmark.circle" icon-android="error" />
    </native:column>
</native:scroll-view>
