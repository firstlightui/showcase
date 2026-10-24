<native:scroll-view class="w-full flex-1">
    <native:column class="w-full p-4 gap-3">
        <native:text class="text-sm text-theme-on-surface-variant">{{ $post->created_at->diffForHumans() }}</native:text>
        <native:text class="text-base text-theme-on-surface">{{ $post->body }}</native:text>
    </native:column>
</native:scroll-view>
