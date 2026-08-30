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

<section {{ $attributes->merge(['class' => 'py-12 md:py-20']) }}
         x-data="{ inView: false }"
         x-intersect.once="inView = true">
    <div class="transition-all duration-1000 transform"
         :class="inView ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-8'">
        <x-frontend.section-title
            badge="{{ __('frontend.media_center') }}"
            :title="$sectionTitle ?? __('frontend.latest_news_and_activities')"
            :description="$sectionDesc ?? __('frontend.news_preview_desc')"
        />
    </div>

    @if ($newsItems->isNotEmpty())
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 lg:gap-8 transition-all duration-1000 transform delay-200"
             :class="inView ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-12'">
            @foreach ($newsItems as $news)
                @php
                    $newsTitle   = $locale === 'ar' ? ($news->title_ar ?: '') : ($news->title_en ?: '');
                    $newsExcerpt = $locale === 'ar' ? ($news->excerpt_ar ?: '') : ($news->excerpt_en ?: '');
                    $newsImg     = \App\Helpers\MediaHelper::url($news, 'news_images', 'image', 'thumb');
                    $displayImg  = $newsImg ?: "data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='400' height='225' viewBox='0 0 400 225'><rect width='100%' height='100%' fill='%23F3F4F6'/><text x='50%' y='50%' dominant-baseline='middle' text-anchor='middle' font-family='sans-serif' font-size='14' fill='%239CA3AF'>" . ($locale === 'ar' ? 'مؤسسة الأثر' : 'Al-Athar Foundation') . "</text></svg>";
                @endphp
                <x-frontend.card :hoverable="true" :padding="'none'" class="flex flex-col justify-between h-full group">
                    <div class="overflow-hidden rounded-t-2xl shrink-0">
                        <a href="{{ route('news.show', $news->slug) }}" class="block">
                            <img src="{{ $displayImg }}" alt="{{ $newsTitle }}" loading="lazy"
                                 class="w-full aspect-video object-cover transition-transform duration-500 group-hover:scale-105">
                        </a>
                    </div>

                    <div class="p-6 flex-1 flex flex-col justify-between">
                        <div class="space-y-4">
                            <span class="text-xs font-semibold text-primary dark:text-primary-light">
                                {{ $news->published_at?->translatedFormat('d M Y') }}
                            </span>
                            <h3 class="text-lg font-bold text-text-primary dark:text-gray-100 line-clamp-2">
                                <a href="{{ route('news.show', $news->slug) }}" class="hover:text-primary transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary rounded-md">
                                    {{ $newsTitle }}
                                </a>
                            </h3>
                            @if ($newsExcerpt)
                                <p class="text-sm text-text-primary/75 dark:text-gray-300 leading-relaxed line-clamp-3">
                                    {{ $newsExcerpt }}
                                </p>
                            @endif
                        </div>
                    </div>

                    <div class="px-6 pb-6 pt-4 border-t border-primary-light/10">
                        <x-frontend.button :href="route('news.show', $news->slug)" variant="ghost" size="sm" class="p-0 text-primary font-semibold flex items-center gap-1.5 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary rounded-md">
                            <span>{{ __('frontend.read_more') }}</span>
                            <svg class="w-4 h-4 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </x-frontend.button>
                    </div>
                </x-frontend.card>
            @endforeach
        </div>

        <div class="text-center pt-10">
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
</section>
