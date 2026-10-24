<native:scroll-view class="w-full flex-1 bg-theme-background">
    <native:column class="w-full p-4 pb-8 gap-5">
        <native:text class="text-base text-theme-on-surface select-text">{{ $article['description'] }}</native:text>
        <firstlight:select :options="$chapters" native:model="chapter" label="Article section" helper="All sections and examples are available offline." />
        @if ($example)
            <firstlight:button variant="secondary" @press="tryExample">Try the live example</firstlight:button>
        @endif
        <native:text class="text-xl font-semibold text-theme-on-surface">{{ $chapterContent['title'] }}</native:text>
        @foreach ($chapterContent['blocks'] as $block)
            @if ($block['type'] === 'code')
                <native:column class="w-full gap-2">
                    <native:text class="text-sm font-semibold text-theme-on-surface">{{ $block['language'] ?: 'Code' }} · Touch and hold to copy</native:text>
                    <native:text :text="$block['text']" class="w-full p-3 text-sm font-mono whitespace-pre-wrap text-theme-on-surface bg-theme-surface rounded-lg select-text" />
                </native:column>
            @elseif ($block['type'] === 'heading')
                <native:text class="text-lg font-semibold text-theme-on-surface">{{ $block['text'] }}</native:text>
            @elseif ($block['type'] === 'definition')
                <native:column class="w-full gap-2">
                    <native:text class="text-base font-semibold text-theme-on-surface select-text">{{ $block['label'] }}</native:text>
                    <native:text :text="$block['text']" class="text-base whitespace-pre-line text-theme-on-surface select-text" />
                </native:column>
            @elseif ($block['type'] === 'image')
                <native:column class="w-full gap-2">
                    <native:text class="text-sm text-theme-on-surface">{{ $block['text'] }}</native:text>
                    <native:image :src="$block['src']" :alt="$block['text']" fit="0" class="w-full h-[480]" />
                </native:column>
            @else
                <native:text :text="$block['text']" class="text-base leading-relaxed whitespace-pre-line text-theme-on-surface select-text" />
            @endif
        @endforeach
        @if ($chapterContent['links'])
            <native:column class="w-full gap-3 pt-3">
                <native:text class="text-lg font-semibold text-theme-on-surface">Related reading</native:text>
                @foreach ($chapterContent['links'] as $index => $link)
                    <firstlight:button variant="ghost" @press="openLink({{ $index }})">{{ $link['label'] }}</firstlight:button>
                @endforeach
                <native:text class="text-sm text-theme-on-surface">External references open in your browser and need a connection.</native:text>
            </native:column>
        @endif
    </native:column>
</native:scroll-view>
