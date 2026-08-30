<x-frontend-layout title="{{ __('frontend.news') }}">

    {{-- ═══════════════════════════════════════════════════════════════
         TWO-TONE SECTION HEADING
    ═══════════════════════════════════════════════════════════════ --}}
    <div class="text-center mb-12 space-y-3">
        <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-secondary-light/40 text-primary text-xs font-semibold tracking-widest uppercase">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
            </svg>
            {{ __('frontend.media_center') }}
        </span>

        <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold leading-tight">
            @if(app()->getLocale() === 'ar')
                <span class="bg-gradient-to-r from-primary to-primary-light bg-clip-text text-transparent">أخبار</span>
                <span class="text-text-primary"> المؤسسة</span>
            @else
                <span class="bg-gradient-to-r from-primary to-primary-light bg-clip-text text-transparent">Foundation</span>
                <span class="text-text-primary"> News</span>
            @endif
        </h1>

        <p class="mt-2 text-text-primary/65 max-w-lg mx-auto text-sm sm:text-base leading-relaxed">
            {{ __('frontend.news_page_desc') }}
        </p>

        {{-- Decorative underline --}}
        <div class="flex items-center justify-center gap-2 pt-1">
            <span class="h-px w-12 bg-primary-light/40 rounded-full"></span>
            <span class="w-2 h-2 rounded-full bg-primary"></span>
            <span class="h-px w-24 bg-primary/60 rounded-full"></span>
            <span class="w-2 h-2 rounded-full bg-primary"></span>
            <span class="h-px w-12 bg-primary-light/40 rounded-full"></span>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════════════════
         EDITORIAL NEWS FEED GRID
    ═══════════════════════════════════════════════════════════════ --}}
    @if ($news->count() > 0)

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-8"
             x-data="{ inView: false }"
             x-intersect.once="inView = true">

            @foreach ($news as $index => $item)
                @php
                    $locale     = app()->getLocale();
                    $title      = $locale === 'ar' ? ($item->title_ar ?: '') : ($item->title_en ?: $item->title_ar ?: '');
                    $excerpt    = $locale === 'ar' ? ($item->excerpt_ar ?: '') : ($item->excerpt_en ?: $item->excerpt_ar ?: '');
                    $img        = \App\Helpers\MediaHelper::url($item, 'news_images', 'image', 'card');
                    $displayImg = $img ?: "data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='800' height='450' viewBox='0 0 800 450'><rect width='100%' height='100%' fill='%23EAEAE9'/><circle cx='400' cy='180' r='60' fill='%23B49C6E' opacity='.4'/><rect x='280' y='260' width='240' height='12' rx='6' fill='%23A38B54' opacity='.25'/><rect x='320' y='284' width='160' height='8' rx='4' fill='%23A38B54' opacity='.15'/></svg>";
                    $articleUrl = route('news.show', $item->slug);
                    $isFeatured = $loop->first;

                    // Date for stamp badge
                    $pubDate   = $item->published_at ?? $item->created_at;
                    $dayNum    = $pubDate->format('d');
                    $monthNames = [
                        'ar' => ['يناير','فبراير','مارس','أبريل','مايو','يونيو',
                                 'يوليو','أغسطس','سبتمبر','أكتوبر','نوفمبر','ديسمبر'],
                        'en' => ['JAN','FEB','MAR','APR','MAY','JUN',
                                 'JUL','AUG','SEP','OCT','NOV','DEC'],
                    ];
                    $monthName = $monthNames[$locale][(int)$pubDate->format('n') - 1];

                    // Category label
                    $categoryLabel = $locale === 'ar' ? 'خبر' : 'News';
                    $featuredLabel = $locale === 'ar' ? 'مميز' : 'Featured';
                    $readLabel     = __('frontend.read_news');
                    $shareLabel    = $locale === 'ar' ? 'مشاركة' : 'Share';
                    $copyLabel     = $locale === 'ar' ? 'نسخ الرابط' : 'Copy Link';
                    $copiedLabel   = $locale === 'ar' ? 'تم النسخ ✓' : 'Copied ✓';
                @endphp

                @if($isFeatured)
                    {{-- ─────────────────────────────────────────────────────────
                         FEATURED ARTICLE — spans 2 columns on desktop
                    ───────────────────────────────────────────────────────── --}}
                    <article
                        class="group lg:col-span-2 rounded-2xl overflow-hidden bg-white shadow-sm
                               hover:shadow-xl transition-all duration-300 hover:-translate-y-1
                               border border-primary-light/10 flex flex-col"
                        :class="inView ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-8'"
                        style="transition: opacity 700ms ease, transform 700ms ease; transition-delay: 0ms;"
                        x-data="{ shareOpen: false, copied: false }">

                        {{-- Image with overlapping tags --}}
                        <div class="relative overflow-hidden shrink-0 aspect-[16/9]">
                            <a href="{{ $articleUrl }}" class="block h-full" tabindex="-1" aria-hidden="true">
                                <img src="{{ $displayImg }}" alt="{{ $title }}" loading="eager"
                                     class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
                            </a>

                            {{-- Gradient overlay --}}
                            <div class="absolute inset-0 bg-gradient-to-t from-text-primary/20 via-transparent to-transparent pointer-events-none"></div>

                            {{-- Top-start: Category pill --}}
                            <div class="absolute top-4 start-4 flex gap-2 flex-wrap">
                                <span class="px-3 py-1 rounded-full text-xs font-semibold bg-secondary-light/90 text-text-primary backdrop-blur-sm">
                                    {{ $categoryLabel }}
                                </span>
                                <span class="px-3 py-1 rounded-full text-xs font-bold bg-primary text-white shadow-sm">
                                    {{ $featuredLabel }}
                                </span>
                            </div>

                            {{-- Top-end: Date stamp badge --}}
                            <div class="absolute top-4 end-4 w-14 h-14 rounded-full bg-white shadow-md
                                        flex flex-col items-center justify-center rotate-3 rtl:-rotate-3
                                        ring-2 ring-primary-light/20">
                                <span class="text-base font-extrabold text-primary leading-none tabular-nums">{{ $dayNum }}</span>
                                <span class="text-[9px] uppercase tracking-wide text-text-primary/60 leading-none mt-0.5">{{ $monthName }}</span>
                            </div>
                        </div>

                        {{-- Content --}}
                        <div class="p-6 sm:p-8 flex flex-col flex-1 gap-4">
                            <div class="space-y-3 flex-1">
                                <h2 class="text-2xl sm:text-3xl font-bold text-text-primary leading-snug line-clamp-2
                                            hover:text-primary transition-colors duration-200">
                                    <a href="{{ $articleUrl }}"
                                       class="focus:outline-none focus-visible:ring-2 focus-visible:ring-primary rounded">
                                        {{ $title }}
                                    </a>
                                </h2>
                                @if($excerpt)
                                    <p class="text-sm sm:text-base text-text-primary/70 leading-relaxed line-clamp-3">
                                        {{ $excerpt }}
                                    </p>
                                @endif
                            </div>

                            {{-- CTA + Share row --}}
                            <div class="flex items-center gap-3 pt-4 border-t border-primary-light/10">
                                <x-frontend.button :href="$articleUrl" variant="primary" size="sm" class="flex-1 justify-center">
                                    {{ $readLabel }}
                                    <svg class="w-4 h-4 ms-1 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                                    </svg>
                                </x-frontend.button>

                                {{-- Share button + popover --}}
                                <div class="relative shrink-0">
                                    <button type="button"
                                            @click="shareOpen = !shareOpen"
                                            class="w-10 h-10 rounded-full bg-secondary-light/30 hover:bg-secondary-light/60
                                                   flex items-center justify-center transition-colors duration-200
                                                   focus:outline-none focus-visible:ring-2 focus-visible:ring-primary"
                                            :aria-expanded="shareOpen"
                                            aria-label="{{ $shareLabel }}">
                                        <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/>
                                        </svg>
                                    </button>

                                    {{-- Share popover --}}
                                    <div x-show="shareOpen"
                                         x-transition:enter="transition ease-out duration-150"
                                         x-transition:enter-start="opacity-0 scale-95 translate-y-1"
                                         x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                                         x-transition:leave="transition ease-in duration-100"
                                         x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                                         x-transition:leave-end="opacity-0 scale-95 translate-y-1"
                                         @click.outside="shareOpen = false"
                                         @keydown.escape.window="shareOpen = false"
                                         class="absolute bottom-full end-0 mb-2 w-48 bg-white shadow-xl rounded-xl p-2 z-20
                                                border border-primary-light/10"
                                         style="display: none;">

                                        {{-- WhatsApp --}}
                                        <a href="https://wa.me/?text={{ urlencode($title . ' ' . $articleUrl) }}"
                                           target="_blank" rel="noopener noreferrer"
                                           class="flex items-center gap-2.5 px-3 py-2 rounded-lg hover:bg-secondary-light/20
                                                  text-sm text-text-primary transition-colors duration-150">
                                            <svg class="w-4 h-4 text-green-500 shrink-0" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981z"/>
                                            </svg>
                                            <span>WhatsApp</span>
                                        </a>

                                        {{-- X / Twitter --}}
                                        <a href="https://twitter.com/intent/tweet?url={{ urlencode($articleUrl) }}&text={{ urlencode($title) }}"
                                           target="_blank" rel="noopener noreferrer"
                                           class="flex items-center gap-2.5 px-3 py-2 rounded-lg hover:bg-secondary-light/20
                                                  text-sm text-text-primary transition-colors duration-150">
                                            <svg class="w-4 h-4 shrink-0" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                                            </svg>
                                            <span>X / Twitter</span>
                                        </a>

                                        {{-- Divider --}}
                                        <div class="my-1 border-t border-gray-100"></div>

                                        {{-- Copy link --}}
                                        <button type="button"
                                                @click="
                                                    navigator.clipboard.writeText('{{ $articleUrl }}')
                                                        .then(() => {
                                                            copied = true;
                                                            setTimeout(() => { copied = false; shareOpen = false; }, 2000);
                                                        });
                                                "
                                                class="flex items-center gap-2.5 px-3 py-2 rounded-lg w-full text-start
                                                       hover:bg-secondary-light/20 text-sm text-text-primary transition-colors duration-150">
                                            <svg x-show="!copied" class="w-4 h-4 text-primary shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                            </svg>
                                            <svg x-show="copied" class="w-4 h-4 text-green-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                            </svg>
                                            <span x-text="copied ? '{{ $copiedLabel }}' : '{{ $copyLabel }}'"></span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </article>

                @else
                    {{-- ─────────────────────────────────────────────────────────
                         STANDARD ARTICLE CARD
                    ───────────────────────────────────────────────────────── --}}
                    <article
                        class="group rounded-2xl overflow-visible bg-white shadow-sm
                               hover:shadow-lg transition-all duration-300 hover:-translate-y-1
                               border border-primary-light/10 flex flex-col"
                        :class="inView ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-8'"
                        style="transition: opacity 700ms ease, transform 700ms ease; transition-delay: {{ min($index, 8) * 100 }}ms;"
                        x-data="{ shareOpen: false, copied: false }">

                        {{-- Image container — relative so the date stamp overflows it --}}
                        <div class="relative overflow-visible rounded-t-2xl shrink-0">
                            {{-- The actual image clips inside --}}
                            <div class="overflow-hidden rounded-t-2xl">
                                <a href="{{ $articleUrl }}" class="block" tabindex="-1" aria-hidden="true">
                                    <img src="{{ $displayImg }}" alt="{{ $title }}" loading="lazy"
                                         class="w-full aspect-video object-cover transition-transform duration-500 group-hover:scale-105">
                                </a>
                            </div>

                            {{-- Category pill — top-start inside image --}}
                            <div class="absolute top-3 start-3">
                                <span class="px-2.5 py-1 rounded-full text-xs font-semibold
                                             bg-secondary-light/80 text-text-primary backdrop-blur-sm shadow-sm">
                                    {{ $categoryLabel }}
                                </span>
                            </div>

                            {{-- Date stamp — top-end, slightly overflowing, rotated --}}
                            <div class="absolute -top-3 end-4 z-10
                                        w-14 h-14 rounded-full bg-white shadow-md
                                        flex flex-col items-center justify-center
                                        rotate-3 rtl:-rotate-3
                                        ring-2 ring-primary-light/25
                                        transition-transform duration-300 group-hover:rotate-0">
                                <span class="text-base font-extrabold text-primary leading-none tabular-nums">{{ $dayNum }}</span>
                                <span class="text-[9px] uppercase tracking-wide text-text-primary/60 leading-none mt-0.5">{{ $monthName }}</span>
                            </div>
                        </div>

                        {{-- Content --}}
                        <div class="p-5 flex flex-col flex-1 gap-3">
                            <div class="space-y-2 flex-1 pt-2">
                                <h3 class="text-lg font-bold text-text-primary leading-snug line-clamp-2
                                            hover:text-primary transition-colors duration-200">
                                    <a href="{{ $articleUrl }}"
                                       class="focus:outline-none focus-visible:ring-2 focus-visible:ring-primary rounded">
                                        {{ $title }}
                                    </a>
                                </h3>
                                @if($excerpt)
                                    <p class="text-sm text-text-primary/70 leading-relaxed line-clamp-2">
                                        {{ $excerpt }}
                                    </p>
                                @endif
                            </div>

                            {{-- CTA + Share --}}
                            <div class="flex items-center gap-2 pt-3 border-t border-primary-light/10">
                                <x-frontend.button :href="$articleUrl" variant="outline" size="sm" class="flex-1 justify-center">
                                    {{ $readLabel }}
                                </x-frontend.button>

                                {{-- Share icon button --}}
                                <div class="relative shrink-0">
                                    <button type="button"
                                            @click="shareOpen = !shareOpen"
                                            class="w-9 h-9 rounded-full bg-secondary-light/30 hover:bg-secondary-light/60
                                                   flex items-center justify-center transition-colors duration-200
                                                   focus:outline-none focus-visible:ring-2 focus-visible:ring-primary"
                                            :aria-expanded="shareOpen"
                                            aria-label="{{ $shareLabel }}">
                                        <svg class="w-3.5 h-3.5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/>
                                        </svg>
                                    </button>

                                    {{-- Share popover --}}
                                    <div x-show="shareOpen"
                                         x-transition:enter="transition ease-out duration-150"
                                         x-transition:enter-start="opacity-0 scale-95 translate-y-1"
                                         x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                                         x-transition:leave="transition ease-in duration-100"
                                         x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                                         x-transition:leave-end="opacity-0 scale-95 translate-y-1"
                                         @click.outside="shareOpen = false"
                                         @keydown.escape.window="shareOpen = false"
                                         class="absolute bottom-full end-0 mb-2 w-44 bg-white shadow-xl rounded-xl p-2 z-20
                                                border border-primary-light/10"
                                         style="display: none;">

                                        <a href="https://wa.me/?text={{ urlencode($title . ' ' . $articleUrl) }}"
                                           target="_blank" rel="noopener noreferrer"
                                           class="flex items-center gap-2.5 px-3 py-2 rounded-lg hover:bg-secondary-light/20
                                                  text-sm text-text-primary transition-colors duration-150">
                                            <svg class="w-4 h-4 text-green-500 shrink-0" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981z"/>
                                            </svg>
                                            <span>WhatsApp</span>
                                        </a>

                                        <a href="https://twitter.com/intent/tweet?url={{ urlencode($articleUrl) }}&text={{ urlencode($title) }}"
                                           target="_blank" rel="noopener noreferrer"
                                           class="flex items-center gap-2.5 px-3 py-2 rounded-lg hover:bg-secondary-light/20
                                                  text-sm text-text-primary transition-colors duration-150">
                                            <svg class="w-4 h-4 shrink-0" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                                            </svg>
                                            <span>X / Twitter</span>
                                        </a>

                                        <div class="my-1 border-t border-gray-100"></div>

                                        <button type="button"
                                                @click="
                                                    navigator.clipboard.writeText('{{ $articleUrl }}')
                                                        .then(() => {
                                                            copied = true;
                                                            setTimeout(() => { copied = false; shareOpen = false; }, 2000);
                                                        });
                                                "
                                                class="flex items-center gap-2.5 px-3 py-2 rounded-lg w-full text-start
                                                       hover:bg-secondary-light/20 text-sm text-text-primary transition-colors duration-150">
                                            <svg x-show="!copied" class="w-4 h-4 text-primary shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                            </svg>
                                            <svg x-show="copied" class="w-4 h-4 text-green-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                            </svg>
                                            <span x-text="copied ? '{{ $copiedLabel }}' : '{{ $copyLabel }}'"></span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </article>
                @endif

            @endforeach
        </div>

        <div class="mt-10">
            <x-frontend.pagination :paginator="$news" />
        </div>

    @else
        <x-frontend.empty-state
            :title="__('frontend.no_news_available')"
            :description="__('frontend.news_coming_soon')"
        />
    @endif

</x-frontend-layout>
