@php
    $locale   = app()->getLocale();
    $title    = $locale === 'ar' ? ($news->title_ar ?: '') : ($news->title_en ?: '');
    $content  = $locale === 'ar' ? ($news->content_ar ?: '') : ($news->content_en ?: '');
    $excerpt  = $locale === 'ar' ? ($news->excerpt_ar ?: '') : ($news->excerpt_en ?: '');
    $img      = \App\Helpers\MediaHelper::url($news, 'news_images', 'image', 'detail');

    $extLink  = $locale === 'ar' ? ($news->external_link_ar ?? $news->external_link) : ($news->external_link_en ?? $news->external_link);
    $seo      = $news->seoMeta;
    $metaDesc = $seo ? ($locale === 'ar' ? ($seo->meta_description_ar ?: '') : ($seo->meta_description_en ?: '')) : $excerpt;
@endphp

<x-frontend-layout :model="$news">

    <div class="max-w-4xl mx-auto space-y-8">
        <!-- Back Navigation -->
        <div>
            <x-frontend.button :href="route('news.index')" variant="ghost" size="sm">
                {{ app()->getLocale() === 'ar' ? '→' : '←' }} {{ __('frontend.back_to_news') }}
            </x-frontend.button>
        </div>

        <!-- Article Card -->
        <article class="bg-white dark:bg-gray-800 border border-primary-light/20 rounded-3xl p-6 sm:p-10 space-y-6 shadow-sm">
            @if ($img)
                <div class="overflow-hidden rounded-2xl aspect-video">
                    <img src="{{ $img }}" alt="{{ $title }}" loading="lazy" class="w-full h-full object-cover">
                </div>
            @endif

            <div class="space-y-4">
                <div class="flex items-center gap-3 text-sm text-primary dark:text-primary-light font-semibold">
                    <span>📅 {{ $news->published_at?->translatedFormat('d F Y') }}</span>
                    @if ($news->author)
                        <span>• ✍️ {{ $news->author->name }}</span>
                    @endif
                </div>

                <h1 class="text-2xl sm:text-4xl font-bold text-text-primary dark:text-gray-100 leading-tight">
                    {{ $title }}
                </h1>

                @if ($excerpt)
                    <p class="text-lg text-text-primary/80 dark:text-gray-300 font-medium leading-relaxed italic p-4 rounded-xl bg-secondary-light/20 border-start-4 border-primary">
                        {{ $excerpt }}
                    </p>
                @endif

                @if ($content)
                    <div class="text-base text-text-primary/85 dark:text-gray-200 leading-relaxed space-y-4 prose max-w-none pt-4 border-t border-primary-light/20">
                        {!! nl2br(e($content)) !!}
                    </div>
                @endif
            </div>

            @if ($extLink && \App\Helpers\MediaHelper::shouldShowExternalLink($news, $extLink, 'news_images', 'image'))
                <div class="pt-6 border-t border-primary-light/20">
                    <x-frontend.button :href="$extLink" variant="primary" size="md" target="_blank" rel="noopener">
                        {{ __('frontend.source_details_link') }} ↗
                    </x-frontend.button>
                </div>
            @endif
        </article>
    </div>

</x-frontend-layout>
