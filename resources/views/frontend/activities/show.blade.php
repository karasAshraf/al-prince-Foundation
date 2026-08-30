@php
    $locale   = app()->getLocale();
    $title    = $locale === 'ar' ? ($activity->title_ar ?: '') : ($activity->title_en ?: $activity->title_ar);
    $content  = $locale === 'ar' ? ($activity->description_ar ?: '') : ($activity->description_en ?: $activity->description_ar);
    $img      = \App\Helpers\MediaHelper::url($activity, 'featured_image', 'image', 'detail');
    $seo      = $activity->seoMeta;
    $metaDesc = $seo ? ($locale === 'ar' ? ($seo->meta_description_ar ?: '') : ($seo->meta_description_en ?: $seo->meta_description_ar)) : '';
@endphp

<x-frontend-layout :model="$activity">

    <div class="max-w-4xl mx-auto space-y-8">
        <!-- Back Navigation -->
        <div>
            <x-frontend.button :href="route('activities.index')" variant="ghost" size="sm">
                {{ app()->getLocale() === 'ar' ? '→' : '←' }} {{ __('frontend.back_to_activities') }}
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
                <h1 class="text-2xl sm:text-4xl font-bold text-text-primary dark:text-gray-100 leading-tight">
                    {{ $title }}
                </h1>

                @if ($content)
                    <div class="text-base text-text-primary/85 dark:text-gray-200 leading-relaxed space-y-4 prose max-w-none pt-4 border-t border-primary-light/20 whitespace-pre-line">
                        {{ $content }}
                    </div>
                @endif
            </div>

            {{-- Gallery Slider / Grid --}}
            @php $galleryItems = $activity->getMedia('gallery'); @endphp
            @if($galleryItems->count() > 0)
                <div class="pt-6 border-t border-primary-light/20">
                    <h3 class="text-lg font-bold text-text-primary dark:text-gray-100 mb-4">{{ __('frontend.activity_gallery') }}</h3>
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                        @foreach($galleryItems as $mediaItem)
                            <a href="{{ $mediaItem->getUrl() }}" target="_blank" class="block aspect-square overflow-hidden rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm group">
                                <img src="{{ $mediaItem->hasGeneratedConversion('gallery_thumb') ? $mediaItem->getUrl('gallery_thumb') : $mediaItem->getUrl() }}" alt="{{ $mediaItem->name }}" loading="lazy"
                                     class="w-full h-full object-cover transition duration-300 group-hover:scale-105">
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        </article>
    </div>

</x-frontend-layout>
