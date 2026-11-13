<native:scroll-view class="w-full flex-1 bg-theme-background">
    <native:column class="w-full p-4 pb-8 gap-5">
        <native:text class="text-sm font-semibold text-theme-on-surface">On-device checks</native:text>
        <native:text class="text-sm text-theme-on-surface-variant">Each button in the first four groups sets haptic, so the phone plays that pattern as the tap lands, without waiting for PHP: Apple's feedback generators on iOS, HapticFeedbackConstants on Android. Turn off System Haptics on iOS or Touch feedback on Android and every button here goes silent.</native:text>

        <firstlight:switch
            ref="hapticsEnabled"
            native:model.live="hapticsEnabled"
            label="Play haptics"
            helper="Haptics::setEnabled() turns every Firstlight haptic on or off for this install. The device remembers it."
        />

        @foreach ($groups as $title => $haptics)
            <native:column class="w-full gap-3">
                <native:text class="text-sm font-semibold text-theme-on-surface">{{ $title }}</native:text>
                @foreach (array_chunk($haptics, 2, true) as $pair)
                    <native:row class="w-full gap-3">
                        @foreach ($pair as $haptic => $label)
                            <firstlight:button
                                class="flex-1"
                                variant="secondary"
                                size="sm"
                                haptic="{{ $haptic }}"
                                @press="played('{{ $haptic }}')"
                            >
                                {{ $label }}
                            </firstlight:button>
                        @endforeach
                        @if (count($pair) === 1)
                            <native:column class="flex-1" />
                        @endif
                    </native:row>
                @endforeach
            </native:column>
        @endforeach

        <native:column class="w-full gap-3">
            <native:text class="text-sm font-semibold text-theme-on-surface">Impacts from PHP</native:text>
            <native:text class="text-sm text-theme-on-surface-variant">Haptics::impact() plays a physical weight a moment after the tap, once PHP runs.</native:text>
            @foreach (array_chunk($impacts, 2) as $pair)
                <native:row class="w-full gap-3">
                    @foreach ($pair as $weight)
                        <firstlight:button class="flex-1" variant="secondary" size="sm" @press="impact('{{ $weight }}')">
                            {{ ucfirst($weight) }}
                        </firstlight:button>
                    @endforeach
                    @if (count($pair) === 1)
                        <native:column class="flex-1" />
                    @endif
                </native:row>
            @endforeach
        </native:column>

        <native:column class="w-full gap-3">
            <native:text class="text-sm font-semibold text-theme-on-surface">Feedback</native:text>
            <native:text class="text-sm text-theme-on-surface-variant">Feedback sent from a tap plays its outcome. One tap plays one outcome, the strongest, so sending a success and a danger together plays error.</native:text>
            <firstlight:button variant="success" @press="sendSuccess">Send success</firstlight:button>
            <firstlight:button variant="destructive" @press="sendDanger">Send danger</firstlight:button>
            <firstlight:button variant="secondary" @press="sendBoth">Send both</firstlight:button>
        </native:column>

        <native:text class="text-sm text-theme-on-surface">Last played: {{ $lastPlayed }}</native:text>
    </native:column>
</native:scroll-view>
