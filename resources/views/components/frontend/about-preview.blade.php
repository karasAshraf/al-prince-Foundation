@props(['section'])

@php
    $locale = app()->getLocale();
    $title  = $locale === 'ar' ? $section->title_ar : ($section->title_en ?? $section->title_ar);
    $desc   = $locale === 'ar' ? $section->description_ar : ($section->description_en ?? $section->description_ar);
    $img    = \App\Helpers\MediaHelper::url($section, 'about_images', 'image', 'card');
@endphp

<section {{ $attributes->merge(['class' => 'py-12 md:py-20']) }}
         x-data="{ inView: false }"
         x-intersect.once="inView = true">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-center">

        <!-- Visual Column -->
        <div class="lg:col-span-5 order-2 lg:order-1 transition-all duration-1000 transform"
             :class="inView ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-12'">
            @if ($img)
                <div class="group">
                    <x-frontend.media-image :src="$img" :alt="$title ?? ''" aspect-ratio="4/3" class="object-cover" />
                </div>
            @else
                <div class="relative rounded-3xl bg-primary/10 border border-primary-light/30 p-8 space-y-6">
                    <div class="w-14 h-14 rounded-2xl bg-primary text-white flex items-center justify-center font-bold text-2xl">
                        {{ $locale === 'ar' ? 'أ' : 'A' }}
                    </div>
                    @if ($title)
                        <h3 class="text-xl font-bold text-text-primary dark:text-gray-100">{{ $title }}</h3>
                    @endif
                    @if ($desc)
                        <p class="text-sm text-text-primary/80 dark:text-gray-300 leading-relaxed">{{ $desc }}</p>
                    @endif
                </div>
            @endif
        </div>

        <!-- Text Column -->
        <div class="lg:col-span-7 space-y-6 order-1 lg:order-2 transition-all duration-1000 transform delay-200"
             :class="inView ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-12'">
            <x-frontend.section-title align="start"
                badge="{{ __('frontend.about_foundation') }}"
                :title="$title ?? ''"
                :description="$desc ?? ''"
            />

            <div class="pt-4">
                <x-frontend.button :href="route('about.index')" variant="primary">
                    {{ __('frontend.more_about_us') }}
                </x-frontend.button>
            </div>
        </div>
    </div>
</section>
