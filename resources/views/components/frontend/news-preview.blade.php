@props([
    'newsItems',
    'section' => null,
])

@php
    $locale      = app()->getLocale();
    $sectionTitle = $section
        ? ($locale === 'ar' ? $section->title_ar : ($section->title_en ?? $section->title_ar))
        : null;
    $sectionDesc = $section
        ? ($locale === 'ar' ? $section->description_ar : ($section->description_en ?? $section->description_ar))
        : null;
@endphp

<x-frontend.section 
    badge="{{ __('frontend.media_center') }}"
    :title="$sectionTitle ?? __('frontend.latest_news_and_activities')"
    :description="$sectionDesc ?? __('frontend.news_preview_desc')"
    index="4"
    align="center"
    x-data="{ inView: false }"
    x-intersect.once="inView = true"
>
    @if ($newsItems->isNotEmpty())
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-8 transition-all duration-1000 transform delay-200"
             :class="inView ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-12'">
            @foreach ($newsItems as $news)
                <x-frontend.news-card :news="$news" />
            @endforeach
        </div>

        <div class="text-center pt-10 transition-all duration-1000 transform delay-300"
             :class="inView ? 'opacity-100 scale-100' : 'opacity-0 scale-95'">
            <x-frontend.button :href="route('news.index')" variant="outline">
                {{ __('frontend.view_all_news') }}
            </x-frontend.button>
        </div>
    @else
        <x-frontend.empty-state
            :title="__('frontend.no_news_available')"
            :description="__('frontend.news_coming_soon')"
        />
    @endif
</x-frontend.section>
