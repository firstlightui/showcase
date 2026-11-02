<native:column class="flex-1 items-center justify-center gap-6 p-8 bg-theme-background">
    <native:icon name="lock" ios="lock.fill" android="lock" class="text-6xl text-theme-primary" />
    <native:text class="text-2xl font-semibold text-theme-on-background">Locked</native:text>

    @if ($failed)
        <firstlight:callout message="Couldn't confirm it's you. Try again." tone="warning" />
    @endif

    <firstlight:button :loading="$prompting" @press="unlock">Unlock</firstlight:button>
</native:column>
