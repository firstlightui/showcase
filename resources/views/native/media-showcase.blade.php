<native:scroll-view class="w-full flex-1 bg-theme-background">
    <native:column class="w-full p-4 gap-5">
        <native:column class="w-full gap-3">
            <native:text class="text-sm font-semibold text-theme-on-surface">Image with required aspect</native:text>
            <firstlight:media
                mode="image"
                native:model="avatar"
                label="Profile photo"
                helper="Square crop required"
                aspect="1:1"
                disk="mobile_public"
                directory="avatars"
                required
                @change="avatarChosen"
                @clear="avatarCleared"
            />
        </native:column>

        <native:column class="w-full gap-3">
            <native:text class="text-sm font-semibold text-theme-on-surface">Optional freeform crop</native:text>
            <firstlight:media
                mode="image"
                native:model="optionalCrop"
                label="Attachment"
                helper="Crop is optional"
                crop="optional"
                @change="optionalCropChosen"
                @clear="optionalCropCleared"
            />
        </native:column>

        <native:column class="w-full gap-3">
            <native:text class="text-sm font-semibold text-theme-on-surface">Document</native:text>
            <firstlight:media
                mode="document"
                native:model="document"
                label="Contract"
                helper="PDF up to 10 MB"
                @change="documentChosen"
                @clear="documentCleared"
            />
        </native:column>

        <native:column class="w-full gap-3">
            <native:text class="text-sm font-semibold text-theme-on-surface">Disabled</native:text>
            <firstlight:media
                mode="image"
                :value="$disabled"
                label="Locked photo"
                disabled
            />
        </native:column>
    </native:column>
</native:scroll-view>
