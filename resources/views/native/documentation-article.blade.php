<native:scroll-view native:key="page-{{ $page }}" class="w-full flex-1 bg-theme-background">
    <native:column class="w-full px-4 pt-2 pb-10 gap-8">
        @if ($page === 0)
            <native:column class="w-full gap-4">
                <native:text class="text-lg leading-snug text-theme-on-surface-variant select-text">{{ $article['description'] }}</native:text>
                @if ($example)
                    <firstlight:button variant="secondary" icon="play" icon-ios="play.circle" icon-android="play_circle" @press="tryExample">Try the live example</firstlight:button>
                @endif
            </native:column>
        @endif
        @if (count($chapters) > 1)
            <firstlight:select :options="$chapters" native:model="chapter" label="On this page" />
        @endif

        @foreach ($sections as $sectionIndex => $section)
            <native:column class="w-full gap-4">
                @unless ($page === 0 && $sectionIndex === 0 && $section['title'] === 'Overview')
                    <native:text class="text-2xl font-bold text-theme-on-surface">{{ $section['title'] }}</native:text>
                @endunless
                @foreach ($section['blocks'] as $block)
                    @if ($block['type'] === 'code')
                        <native:column class="w-full gap-1">
                            @if ($block['language'] !== '')
                                <native:text class="text-xs font-semibold text-theme-on-surface-variant">{{ strtoupper($block['language']) }}</native:text>
                            @endif
                            <native:text :text="$block['text']" class="w-full p-3 text-[13] leading-snug font-mono whitespace-pre-wrap text-theme-on-surface bg-theme-surface border border-theme-outline-variant rounded-lg select-text" />
                        </native:column>
                    @elseif ($block['type'] === 'heading')
                        <native:text class="pt-2 text-lg font-semibold text-theme-on-surface">@include('native.partials.documentation-runs', ['inline' => $block])</native:text>
                    @elseif ($block['type'] === 'item')
                        <native:row class="w-full gap-2 {{ $block['depth'] > 0 ? 'pl-5' : '' }}">
                            <native:text class="w-[20] text-base leading-relaxed text-theme-on-surface-variant">{{ $block['marker'] }}</native:text>
                            <native:text class="flex-1 text-base leading-relaxed text-theme-on-surface select-text">@include('native.partials.documentation-runs', ['inline' => $block])</native:text>
                        </native:row>
                    @elseif ($block['type'] === 'definition')
                        @php
                            $body = count($block['items']) === 1 ? $block['items'] : array_filter($block['items'], fn (array $item): bool => $item['label'] === 'Purpose');
                            $details = array_diff_key($block['items'], $body);
                        @endphp
                        <native:column class="w-full gap-1 pb-4 border-b border-theme-outline-variant">
                            <native:text class="text-base font-semibold text-theme-on-surface select-text">@include('native.partials.documentation-runs', ['inline' => $block['term']])</native:text>
                            @foreach ($body as $item)
                                <native:text class="text-base leading-relaxed text-theme-on-surface select-text">@include('native.partials.documentation-runs', ['inline' => $item])</native:text>
                            @endforeach
                            @foreach ($details as $item)
                                <native:text class="text-sm leading-snug text-theme-on-surface select-text"><native:text :text="$item['label'].'  '" class="text-theme-on-surface-variant" />@include('native.partials.documentation-runs', ['inline' => $item])</native:text>
                            @endforeach
                        </native:column>
                    @elseif ($block['type'] === 'gallery')
                        @foreach ($block['rows'] as $row)
                            <native:row class="w-full gap-3">
                                @foreach ($row as $image)
                                    <native:column class="flex-1 gap-2">
                                        <native:image :src="$image['src']" :alt="$image['text']" fit="0" class="w-full h-[360]" />
                                        <native:text class="text-xs text-center text-theme-on-surface-variant">{{ $image['text'] }}</native:text>
                                    </native:column>
                                @endforeach
                            </native:row>
                        @endforeach
                    @else
                        <native:text class="text-base leading-relaxed text-theme-on-surface select-text">@include('native.partials.documentation-runs', ['inline' => $block])</native:text>
                    @endif
                @endforeach
            </native:column>
        @endforeach

        @if ($previous || $next)
            <native:column class="w-full gap-2 pt-4 border-t border-theme-outline-variant">
                @if ($next)
                    <firstlight:button variant="secondary" icon-trailing="chevron-right" icon-trailing-ios="chevron.right" icon-trailing-android="chevron_right" @press="showSection('{{ $next }}')">Next: {{ $chapters[$next] }}</firstlight:button>
                @endif
                @if ($previous)
                    <firstlight:button variant="ghost" icon="chevron-left" icon-ios="chevron.left" icon-android="chevron_left" @press="showSection('{{ $previous }}')">Previous: {{ $chapters[$previous] }}</firstlight:button>
                @endif
            </native:column>
        @endif
    </native:column>
</native:scroll-view>
