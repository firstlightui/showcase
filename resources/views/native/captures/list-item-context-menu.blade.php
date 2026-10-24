<native:scroll-view class="w-full flex-1 bg-theme-background">
    <native:column class="w-full py-3">
        <firstlight:list-item
            headline="Message from Alex"
            supporting="Long-press for more actions"
            leading-monogram="AL"
            :context-menu="[
                ['method' => 'capturePress', 'label' => 'Reply', 'icon' => 'reply', 'ios' => 'arrowshape.turn.up.left'],
                ['method' => 'capturePress', 'label' => 'Forward', 'icon' => 'forward', 'ios' => 'arrowshape.turn.up.right', 'disabled' => true],
                ['method' => 'capturePress', 'label' => 'Pin', 'icon' => 'push_pin', 'ios' => 'pin'],
                ['method' => 'capturePress', 'label' => 'Delete', 'icon' => 'delete', 'ios' => 'trash', 'role' => 'destructive'],
            ]"
            @press="capturePress"
        />
    </native:column>
</native:scroll-view>
