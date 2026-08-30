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
                <x-frontend.news-card :news="$item" />
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
