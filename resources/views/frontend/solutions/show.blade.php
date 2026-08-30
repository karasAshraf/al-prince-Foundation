@php
    $locale = app()->getLocale();
    $title  = $locale === 'ar' ? $solution->title_ar : ($solution->title_en ?? $solution->title_ar);
    $desc   = $locale === 'ar' ? $solution->description_ar : ($solution->description_en ?? $solution->description_ar);
    $img    = \App\Helpers\MediaHelper::url($solution, 'solution_images', 'image', 'detail');

    $seo    = $solution->seoMeta;
    $metaDesc = $seo ? ($locale === 'ar' ? $seo->meta_description_ar : ($seo->meta_description_en ?? $seo->meta_description_ar)) : null;
@endphp

<x-frontend-layout :model="$solution">

    <div class="max-w-4xl mx-auto space-y-8">
        <!-- Back Navigation -->
        <div>
            <x-frontend.button :href="$backUrl" variant="ghost" size="sm">
                {{ app()->getLocale() === 'ar' ? '→' : '←' }} {{ __('frontend.back_to_solutions') }}
            </x-frontend.button>
        </div>

        <!-- Article Card -->
        <article class="bg-white dark:bg-gray-800 border border-primary-light/20 rounded-3xl p-6 sm:p-10 space-y-6 shadow-sm">
            @if ($img)
                <div class="overflow-hidden rounded-2xl aspect-video">
                    <img src="{{ $img }}" alt="{{ $title }}" width="800" height="600" loading="lazy" class="w-full h-full object-cover">
                </div>
            @endif

            <div class="space-y-4">
                <h1 class="text-2xl sm:text-3xl font-bold text-text-primary dark:text-gray-100">
                    {{ $title }}
                </h1>

                @if ($desc)
                    <div class="text-base text-text-primary/80 dark:text-gray-300 leading-relaxed space-y-4 whitespace-pre-line border-t border-primary-light/20 pt-6">
                        {{ $desc }}
                    </div>
                @endif

                @if ($solution->external_link && \App\Helpers\MediaHelper::shouldShowExternalLink($solution, $solution->external_link, 'solution_images', 'image'))
                    <div class="pt-6 border-t border-primary-light/20">
                        <x-frontend.button :href="$solution->external_link" variant="primary" size="md" target="_blank" rel="noopener">
                            {{ __('frontend.external_link') }} ↗
                        </x-frontend.button>
                    </div>
                @endif
            </div>
        </article>
    </div>

</x-frontend-layout>
