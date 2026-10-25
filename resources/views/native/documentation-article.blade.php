@php
    // Body copy is leaf text so it follows Dynamic Type and Android font scale;
    // composed inline runs render at a fixed size on iOS.
    $plain = fn (array $inline): string => $inline['text'] ?? implode('', array_column($inline['runs'], 'text'));
    $linksOf = fn (array $inline): array => array_values(array_unique(array_column($inline['runs'] ?? [], 'link')));
@endphp
<native:scroll-view class="w-full flex-1 bg-theme-background">
    <native:column class="w-full px-5 pt-1 pb-10 gap-10">
        @foreach ($sections as $sectionIndex => $section)
            <native:column class="w-full gap-3">
                @unless ($page === 0 && $sectionIndex === 0 && $section['title'] === 'Overview')
                    <native:column class="w-full gap-1">
                        <native:column class="w-full h-[1] bg-theme-outline-variant" />
                        <native:text class="pt-3 text-xs font-semibold tracking-wide text-theme-primary">{{ strtoupper($groupLabel) }} · {{ $section['number'] }} OF {{ count($chapters) }}</native:text>
                        <native:text class="text-[22] leading-tight font-bold text-theme-on-surface">{{ $section['title'] }}</native:text>
                    </native:column>
                @endunless
                @foreach ($section['blocks'] as $block)
                    @if ($block['type'] === 'code')
                        <native:column class="w-full gap-1">
                            @if ($block['language'] !== '')
                                <native:text class="text-xs font-semibold text-theme-on-surface-variant">{{ strtoupper($block['language']) }}</native:text>
                            @endif
                            {{-- Lines scroll sideways rather than breaking mid-token. --}}
                            <native:scroll-view axis="horizontal" class="w-full bg-theme-surface rounded-lg">
                                <native:text :text="$block['text']" class="p-3 text-[13] leading-snug font-mono whitespace-pre-wrap text-theme-on-surface select-text" />
                            </native:scroll-view>
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
                        @if ($block['type'] === 'heading')
                            <native:text :text="$plain($block)" class="pt-3 text-[17] font-semibold text-theme-on-surface" />
                        @elseif ($block['type'] === 'item')
                            <native:row class="w-full gap-2 {{ $block['depth'] > 0 ? 'pl-5' : '' }}">
                                <native:text class="w-[20] text-[17] leading-snug text-theme-on-surface-variant">{{ $block['marker'] }}</native:text>
                                <native:text :text="$plain($block)" class="flex-1 text-[17] leading-snug text-theme-on-surface select-text" />
                            </native:row>
                        @elseif ($block['type'] === 'definition')
                            @php
                                $body = count($block['items']) === 1 ? $block['items'] : array_filter($block['items'], fn (array $item): bool => $item['label'] === 'Purpose');
                                $details = array_diff_key($block['items'], $body);
                                $codeTerm = isset($block['term']['runs']) && ! in_array(false, array_map(fn (array $run): bool => ! empty($run['code']) || trim($run['text'], ' ,') === '', $block['term']['runs']), true);
                            @endphp
                            <native:column class="w-full gap-1 pt-1">
                                <native:text :text="$plain($block['term'])" class="text-[17] font-semibold text-theme-on-surface select-text {{ $codeTerm ? 'font-mono' : '' }}" />
                                @foreach ($body as $item)
                                    <native:text :text="$plain($item)" class="text-[17] leading-snug text-theme-on-surface select-text" />
                                @endforeach
                                @foreach ($details as $item)
                                    <native:text :text="$item['label'].': '.$plain($item)" class="text-[15] leading-snug text-theme-on-surface-variant select-text" />
                                @endforeach
                            </native:column>
                        @else
                            <native:text :text="$plain($block)" class="text-[17] leading-snug text-theme-on-surface select-text" />
                        @endif
                        @php($links = [...$linksOf($block), ...($block['type'] === 'definition' ? array_merge(...array_map($linksOf, $block['items'])) : [])])
                        @if ($links !== [])
                            {{-- Real buttons so VoiceOver and TalkBack announce them as actionable. --}}
                            <native:column class="w-full items-start">
                                @foreach ($links as $index)
                                    @php($external = parse_url($article['links'][$index]['url'], PHP_URL_SCHEME) === 'https')
                                    <firstlight:button variant="ghost" :a11y-label="$article['links'][$index]['label']" :a11y-hint="$external ? 'Opens in your browser' : 'Opens the bundled article'" @press="openLink({{ $index }})">{{ ($external ? '↗ ' : '→ ').$article['links'][$index]['label'] }}</firstlight:button>
                                @endforeach
                            </native:column>
                        @endif
                    @endif
                @endforeach
            </native:column>
        @endforeach

        @if ($previous || $next)
            <native:column class="w-full gap-2">
                <native:column class="w-full h-[1] bg-theme-outline-variant" />
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
