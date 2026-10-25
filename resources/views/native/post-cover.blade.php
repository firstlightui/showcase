<native:scroll-view class="w-full flex-1">
    <native:column class="w-full p-4 gap-4">
        <firstlight:media
            mode="image"
            label="Cover photo"
            helper="Cropped to 16:9. Saved when you tap Save cover."
            aspect="16:9"
            directory="covers"
            native:model="cover"
            @change="coverChosen"
            @clear="coverCleared"
        />

        <firstlight:button variant="primary" :loading="$submitting" @press="save">
            Save cover
        </firstlight:button>
    </native:column>
</native:scroll-view>
