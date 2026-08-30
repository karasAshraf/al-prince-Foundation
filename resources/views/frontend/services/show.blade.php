@php
    $locale = app()->getLocale();
    $title  = $locale === 'ar' ? $service->title_ar : ($service->title_en ?? $service->title_ar);
    $desc   = $locale === 'ar' ? $service->description_ar : ($service->description_en ?? $service->description_ar);
    $img    = \App\Helpers\MediaHelper::url($service, 'service_images', 'image', 'detail');

    $seo    = $service->seoMeta;
    $metaDesc = $seo ? ($locale === 'ar' ? $seo->meta_description_ar : ($seo->meta_description_en ?? $seo->meta_description_ar)) : null;
@endphp

<x-frontend-layout :model="$service">

    <div class="max-w-4xl mx-auto space-y-8">
        <!-- Back Navigation -->
        <div>
            <x-frontend.button :href="route('services.index')" variant="ghost" size="sm">
                {{ app()->getLocale() === 'ar' ? '→' : '←' }} {{ __('frontend.back_to_services') }}
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
                <div class="flex items-center gap-3">
                    @if ($service->icon)
                        <span class="text-3xl">{{ $service->icon }}</span>
                    @endif
                    <h1 class="text-2xl sm:text-3xl font-bold text-text-primary dark:text-gray-100">
                        {{ $title }}
                    </h1>
                </div>

                @if ($desc)
                    <div class="text-base text-text-primary/80 dark:text-gray-300 leading-relaxed space-y-4 whitespace-pre-line">
                        {{ $desc }}
                    </div>
                @endif
            </div>

            <x-frontend.external-link-button :model="$service" collection="service_images" />
        </article>
    </div>

</x-frontend-layout>
