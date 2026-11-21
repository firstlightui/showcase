<native:scroll-view class="w-full flex-1 bg-theme-background">
    <native:column class="w-full p-4 gap-4">
        @if (! $canScan)
            <firstlight:callout message="Scanning needs a device with a camera and, on Android, Google Play services." tone="info" />
        @endif

        @if ($error)
            <firstlight:callout :message="$error" tone="warning" />
        @endif

        <firstlight:button :loading="$working" :disabled="! $canScan" @press="scan">Scan a document</firstlight:button>

        @if ($text !== '')
            <native:text class="text-base text-theme-on-background">{{ $text }}</native:text>
        @endif
    </native:column>
</native:scroll-view>
