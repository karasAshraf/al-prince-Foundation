<x-frontend-layout title="{{ __('frontend.news') }}">

    @php
        $hasNewsHero = \App\Models\HeroSlide::active()->where('placement', 'news')->exists();
    @endphp

    @if(!$hasNewsHero)
    {{-- ═══════════════════════════════════════════════════════════════
         TWO-TONE SECTION HEADING
    ═══════════════════════════════════════════════════════════════ --}}
    <div class="text-center mb-12 space-y-3">
        <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-secondary/40 text-primary text-xs font-semibold tracking-widest uppercase">
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

       
        {{-- Decorative underline --}}
        <div class="flex items-center justify-center gap-2 pt-1">
            <span class="h-px w-12 bg-secondary/40 rounded-full"></span>
            <span class="w-2 h-2 rounded-full bg-primary"></span>
            <span class="h-px w-24 bg-primary/60 rounded-full"></span>
            <span class="w-2 h-2 rounded-full bg-primary"></span>
            <span class="h-px w-12 bg-secondary/40 rounded-full"></span>
        </div>
    </div>
    @endif

    {{-- ═══════════════════════════════════════════════════════════════
         EDITORIAL NEWS FEED GRID
    ═══════════════════════════════════════════════════════════════ --}}
    @if ($news->count() > 0)

        <div class="flex flex-col gap-10"
             x-data="{ inView: false }"
             x-intersect.once="inView = true">

            {{-- Featured Article (Latest) --}}
            @php $featured = $news->first(); @endphp
            @if($featured)
            <div class="max-w-5xl mx-auto overflow-hidden rounded-2xl bg-background dark:bg-gray-800 border border-secondary transition-all hover:shadow-lg group grid grid-cols-1 md:grid-cols-2">
                <div class="w-full h-64 md:h-[300px] relative overflow-hidden shrink-0 bg-gray-150">
                    @php
                        $featuredLocale = app()->getLocale();
                        $featuredTitle = $featuredLocale === 'ar' ? ($featured->title_ar ?: '') : ($featured->title_en ?: $featured->title_ar ?: '');
                        $featuredExcerpt = $featuredLocale === 'ar' ? ($featured->excerpt_ar ?: '') : ($featured->excerpt_en ?: $featured->excerpt_ar ?: '');
                        $featuredImg = \App\Helpers\MediaHelper::url($featured, 'news_images', 'image', 'card');
                        $featuredDisplayImg = $featuredImg ?: "data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='800' height='450' viewBox='0 0 800 450'><rect width='100%' height='100%' fill='%23EAEAE9'/><circle cx='400' cy='180' r='60' fill='%23B8974F' opacity='.4'/><rect x='280' y='260' width='240' height='12' rx='6' fill='%23AC8321' opacity='.25'/><rect x='320' y='284' width='160' height='8' rx='4' fill='%23AC8321' opacity='.15'/></svg>";
                        $featuredArticleUrl = route('news.show', $featured->slug);
                        $featuredPubDate = $featured->published_at ?? $featured->created_at;
                    @endphp
                    <a href="{{ $featuredArticleUrl }}" class="block w-full h-full">
                        <img src="{{ $featuredDisplayImg }}" alt="{{ $featuredTitle }}" loading="lazy" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
                    </a>
                </div>
                <div class="w-full p-6 md:p-8 flex flex-col justify-center space-y-4 h-full">
                    <div class="flex items-center justify-between">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-md bg-primary/10 text-primary text-xs font-bold uppercase tracking-wider">
                            {{ __('frontend.latest_news') ?? 'Latest' }}
                        </span>
                        <span class="text-sm font-semibold text-text-primary dark:text-gray-400">
                            {{ $featuredPubDate?->translatedFormat('d M Y') }}
                        </span>
                    </div>
                    
                    <h2 class="text-xl md:text-2xl font-bold text-text-primary dark:text-background leading-tight line-clamp-2">
                        <a href="{{ $featuredArticleUrl }}" class="hover:text-primary transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary rounded-md">
                            {{ $featuredTitle }}
                        </a>
                    </h2>
                    
                    @if ($featuredExcerpt)
                        <p class="text-base text-text-primary dark:text-gray-300 leading-relaxed line-clamp-2">
                            {{ $featuredExcerpt }}
                        </p>
                    @endif
                    
                    <div class="pt-6 border-t border-secondary dark:border-gray-700/50 flex items-center justify-between mt-auto">
                        <a href="{{ $featuredArticleUrl }}" class="inline-flex items-center gap-2 text-sm font-bold text-primary hover:text-secondary transition-all group/link">
                            <span class="relative overflow-hidden">
                                <span class="block">{{ __('frontend.read_more') }}</span>
                                <span class="absolute bottom-0 left-0 w-full h-[2px] bg-primary transform -translate-x-full group-hover/link:translate-x-0 transition-transform duration-300 rtl:translate-x-full rtl:group-hover/link:-translate-x-0"></span>
                            </span>
                            <svg class="w-5 h-5 rtl:rotate-180 transition-transform group-hover/link:translate-x-1 rtl:group-hover/link:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                        </a>
                        
                        <x-frontend.share-buttons :url="url(route('news.show', $featured->slug))" :title="$featuredTitle" />
                    </div>
                </div>
            </div>
            @endif

            {{-- Older Articles List --}}
            @if ($news->count() > 1)
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-8 border-t border-secondary dark:border-gray-700 pt-10">
                    @foreach ($news->slice(1) as $index => $item)
                        @php
                            $itemLocale = app()->getLocale();
                            $itemTitle = $itemLocale === 'ar' ? ($item->title_ar ?: '') : ($item->title_en ?: $item->title_ar ?: '');
                            $itemExcerpt = $itemLocale === 'ar' ? ($item->excerpt_ar ?: '') : ($item->excerpt_en ?: $item->excerpt_ar ?: '');
                            $itemImg = \App\Helpers\MediaHelper::url($item, 'news_images', 'image', 'card');
                            $itemDisplayImg = $itemImg ?: "data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='800' height='450' viewBox='0 0 800 450'><rect width='100%' height='100%' fill='%23EAEAE9'/><circle cx='400' cy='180' r='60' fill='%23B8974F' opacity='.4'/><rect x='280' y='260' width='240' height='12' rx='6' fill='%23AC8321' opacity='.25'/><rect x='320' y='284' width='160' height='8' rx='4' fill='%23AC8321' opacity='.15'/></svg>";
                            $itemArticleUrl = route('news.show', $item->slug);
                            $itemPubDate = $item->published_at ?? $item->created_at;
                        @endphp
                        <article class="flex flex-col bg-background dark:bg-gray-800 rounded-2xl border border-secondary hover:shadow-lg transition-all duration-300 group overflow-hidden h-full">
                            <div class="w-full aspect-[4/3] sm:h-48 overflow-hidden shrink-0 relative">
                                <a href="{{ $itemArticleUrl }}" class="block w-full h-full">
                                    <img src="{{ $itemDisplayImg }}" alt="{{ $itemTitle }}" loading="lazy" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                                </a>
                                <span class="absolute top-4 start-4 px-2.5 py-1 rounded bg-background/95 dark:bg-gray-900/95 text-primary text-xs font-bold shadow-sm">
                                    {{ $itemPubDate?->translatedFormat('d M Y') }}
                                </span>
                            </div>
                            <div class="p-6 flex-1 flex flex-col justify-between">
                                <div class="space-y-3">
                                    <h3 class="text-lg font-bold text-text-primary dark:text-background leading-snug line-clamp-2">
                                        <a href="{{ $itemArticleUrl }}" class="hover:text-primary transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary rounded-md">
                                            {{ $itemTitle }}
                                        </a>
                                    </h3>
                                    @if ($itemExcerpt)
                                        <p class="text-sm text-text-primary dark:text-gray-300 leading-relaxed line-clamp-2">
                                            {{ $itemExcerpt }}
                                        </p>
                                    @endif
                                </div>
                                <div class="mt-4 flex items-center justify-between pt-4 border-t border-secondary dark:border-gray-700/50">
                                    <a href="{{ $itemArticleUrl }}" class="inline-flex items-center gap-1.5 text-sm font-semibold text-primary hover:text-secondary transition-all group/link">
                                        <span class="relative overflow-hidden">
                                            <span class="block">{{ __('frontend.read_more') }}</span>
                                            <span class="absolute bottom-0 left-0 w-full h-[2px] bg-primary transform -translate-x-full group-hover/link:translate-x-0 transition-transform duration-300 rtl:translate-x-full rtl:group-hover/link:-translate-x-0"></span>
                                        </span>
                                    </a>
                                    <x-frontend.share-buttons :url="url(route('news.show', $item->slug))" :title="$itemTitle" />
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>
            @endif
        </div>

        <div class="mt-12">
            <x-frontend.pagination :paginator="$news" />
        </div>

    @else
        <x-frontend.empty-state
            :title="__('frontend.no_news_available')"
            :description="__('frontend.news_coming_soon')"
        />
    @endif

</x-frontend-layout>
