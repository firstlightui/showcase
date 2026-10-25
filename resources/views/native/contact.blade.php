<native:scroll-view class="w-full flex-1 bg-theme-background">
    <native:column class="w-full p-4 gap-3">
        <native:text class="text-base text-theme-on-surface">{{ $contact->email }}</native:text>
        <native:text class="text-base text-theme-on-surface">{{ $contact->phone }}</native:text>
    </native:column>
</native:scroll-view>
