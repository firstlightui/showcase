<native:scroll-view class="w-full flex-1 bg-theme-background">
    <native:column class="w-full p-5 gap-5">
        <firstlight:media
            mode="image"
            native:model="profilePhoto"
            label="Profile photo"
            helper="Square crop required"
            aspect="1:1"
            disk="mobile_public"
            directory="avatars"
            required
            a11y-hint="Choose a square profile photo"
        />

        <firstlight:media
            mode="document"
            native:model="contract"
            label="Contract"
            helper="PDF up to 10 MB"
            disk="mobile_public"
            directory="docs"
        />

        <firstlight:media
            mode="image"
            :value="$disabledPhoto"
            label="Locked photo"
            disabled
        />

        <firstlight:media
            mode="image"
            native:model="photoWithError"
            label="Attachment"
            crop="optional"
            error="Choose a clearer photo."
            required
            a11y-hint="Resolve the attachment error"
        />
    </native:column>
</native:scroll-view>
