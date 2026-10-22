<native:scroll-view class="w-full flex-1 bg-theme-background">
    <native:column class="w-full p-5 gap-5 pb-32">
        <native:text class="text-sm text-theme-on-surface">On-device checks: one swipe per measurement, no button role or duplicate child fragments. Increase text to the largest accessibility size: values wrap, never ellipsize. Metric is display-only; surrounding tiles and grids belong to Mobile UI.</native:text>
        <firstlight:metric label="Events" value="5" detail="last 10h ago" />
        <firstlight:metric label="Users" value="0" detail="affected" tone="info" a11y-label="No affected users" />
        <firstlight:metric label="Response" value="1,234.50 milliseconds" detail="last check" tone="success" />
        <firstlight:metric label="Pending" value="12" tone="warning" />
        <firstlight:metric label="Failures" value="3" detail="since release" tone="danger" />
    </native:column>
</native:scroll-view>
