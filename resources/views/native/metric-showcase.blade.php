<native:scroll-view class="w-full flex-1 bg-theme-background">
    <native:column class="w-full p-5 gap-5 pb-8">
        <native:text class="text-sm text-theme-on-surface">On-device checks: one swipe per measurement, no button role or duplicate child fragments. Increase text to the largest accessibility size: values wrap, never ellipsize. Metric is display-only; surrounding tiles and grids belong to Mobile UI.</native:text>
        <firstlight:metric label="Events" value="5" detail="last 10h ago" />
        <firstlight:metric label="Users" value="0" detail="affected" tone="info" a11y-label="No affected users" />
        <firstlight:metric label="Response" value="1,234.50 milliseconds" detail="last check" tone="success" />
        <firstlight:metric label="Pending" value="12" tone="warning" />
        <firstlight:metric label="Failures" value="3" detail="since release" tone="danger" />

        <native:column class="w-full gap-3">
            <native:text class="text-sm font-semibold text-theme-on-surface">Programmatic updates and hint</native:text>
            <native:text class="text-sm text-theme-on-surface">PHP republishes the value, detail and tone of the same metric. Waiting turns danger at 6 and success at 0; no event is emitted. Screen readers also hear its a11y-hint.</native:text>
            <firstlight:metric
                label="Waiting"
                :value="(string) $waiting"
                :detail="$waiting === 1 ? 'patient in the queue' : 'patients in the queue'"
                :tone="$this->waitingTone()"
                a11y-hint="Updates when a patient checks in or is seen"
            />
            <firstlight:button label="Check in a patient" variant="secondary" @press="addWaiting" />
            <firstlight:button label="Clear the queue" variant="secondary" @press="clearWaiting" />
            <native:text class="text-sm text-theme-on-surface">PHP accepted state: {{ $waiting }} waiting · tone {{ $this->waitingTone() }}</native:text>
        </native:column>
    </native:column>
</native:scroll-view>
