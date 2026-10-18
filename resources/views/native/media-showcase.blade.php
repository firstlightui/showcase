<native:scroll-view class="w-full flex-1 bg-theme-background">
    <native:column class="w-full p-4 gap-5 pb-32">
        <native:text class="text-sm text-theme-on-surface">On-device checks: pick from camera and library, cancel each picker, zoom and confirm or cancel crop, Skip optional crop, replace a selection, then clear. Cancellation must retain the previous value. Try a PDF and an invalid document, then validate. Use sample files only; Clear demo media deletes only this screen's selected copies.</native:text>
        <native:column class="w-full gap-3">
            <native:text class="text-sm font-semibold text-theme-on-surface">Image with required aspect</native:text>
            <firstlight:media
                ref="avatar"
                mode="image"
                native:model="avatar"
                error-for="avatar"
                label="Profile photo"
                helper="Square crop required"
                aspect="1:1"
                disk="mobile_public"
                directory="showcase"
                required
                @change="avatarChosen"
                @clear="avatarCleared"
            />
        </native:column>

        <native:column class="w-full gap-3">
            <native:text class="text-sm font-semibold text-theme-on-surface">Optional freeform crop</native:text>
            <firstlight:media
                ref="optionalCrop"
                mode="image"
                native:model="optionalCrop"
                label="Attachment"
                helper="Crop is optional"
                crop="optional"
                directory="showcase"
                @change="optionalCropChosen"
                @clear="optionalCropCleared"
            />
        </native:column>

        <native:column class="w-full gap-3">
            <native:text class="text-sm font-semibold text-theme-on-surface">Required freeform crop</native:text>
            <firstlight:media
                ref="requiredCrop"
                mode="image"
                native:model="requiredCrop"
                label="Required freeform image"
                crop="required"
                directory="showcase"
                @change="requiredCropChosen"
                @clear="requiredCropCleared"
            />
            <firstlight:media
                ref="uncropped"
                mode="image"
                native:model="uncropped"
                label="Image without crop"
                directory="showcase"
                @change="uncroppedChosen"
                @clear="uncroppedCleared"
            />
        </native:column>

        <native:column class="w-full gap-3">
            <native:text class="text-sm font-semibold text-theme-on-surface">Document</native:text>
            <firstlight:media
                ref="document"
                mode="document"
                native:model="document"
                error-for="document"
                label="Contract"
                helper="PDF up to 10 MB; checked by Validate media"
                directory="showcase"
                @change="documentChosen"
                @clear="documentCleared"
            />
        </native:column>

        <native:column class="w-full gap-3">
            <native:text class="text-sm font-semibold text-theme-on-surface">Disabled</native:text>
            <firstlight:media
                mode="image"
                :value="$avatar"
                label="Locked photo"
                helper="Mirrors the real profile photo; pick and clear must be unavailable."
                disabled
            />
        </native:column>

        <native:column class="w-full gap-2">
            <native:text class="text-sm font-semibold text-theme-on-surface">PHP accepted state</native:text>
            @foreach (['Profile photo' => $avatar, 'Optional crop' => $optionalCrop, 'Required crop' => $requiredCrop, 'Uncropped' => $uncropped, 'Document' => $document] as $name => $media)
                <native:text class="text-sm text-theme-on-surface">{{ $name }}: {{ $media ? $media->mime.' · '.$media->size.' bytes · '.($media->width ?? '—').' × '.($media->height ?? '—').' · '.$media->path : ($name === 'Document' ? 'No document selected' : 'No image selected') }}</native:text>
            @endforeach
            <native:text class="text-sm text-theme-on-surface">{{ $lastOutcome }}</native:text>
            <firstlight:button label="Validate media" @press="validateMedia" />
            <firstlight:button label="Clear demo media" variant="secondary" @press="clearDemoMedia" />
        </native:column>
    </native:column>
</native:scroll-view>
