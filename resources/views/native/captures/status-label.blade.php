<native:scroll-view class="w-full flex-1 bg-theme-background">
    <native:column class="w-full p-5 gap-5">
        <native:text class="text-lg font-semibold text-theme-on-surface">Workflow status</native:text>
        <firstlight:status-label label="Draft" />
        <firstlight:status-label label="In progress" tone="info" />
        <firstlight:status-label label="Ready" tone="success" />
        <firstlight:status-label label="Awaiting review" tone="warning" />
        <firstlight:status-label label="Blocked" tone="danger" />
    </native:column>
</native:scroll-view>
