@props(['section'])

@php
    $locale = app()->getLocale();
    $title  = $locale === 'ar' ? $section->title_ar : ($section->title_en ?? $section->title_ar);
    $desc   = $locale === 'ar' ? $section->description_ar : ($section->description_en ?? $section->description_ar);
    $link   = $section->extra_link ?? route('contact.index');
@endphp

<section {{ $attributes->merge(['class' => 'py-12 md:py-16 bg-primary text-background rounded-3xl p-8 sm:p-12 shadow-xl my-8 relative overflow-hidden']) }}
         x-data="{ inView: false }"
         x-intersect.once="inView = true">
    <!-- Decorative elements -->
    <div class="absolute -top-24 -end-24 w-64 h-64 rounded-full bg-secondary/20 blur-2xl pointer-events-none" aria-hidden="true"></div>
    <div class="absolute -bottom-24 -start-24 w-64 h-64 rounded-full bg-secondary/20 blur-2xl pointer-events-none" aria-hidden="true"></div>

    <div class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-8 text-center md:text-start transition-all duration-1000 transform"
         :class="inView ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-8'">
        <div class="space-y-3 max-w-2xl">
            <x-frontend.badge variant="secondary" size="sm" class="w-fit mx-auto md:mx-0">
                {{ __('frontend.call_to_action') }}
            </x-frontend.badge>

            @if ($title)
                <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold text-background tracking-tight leading-tight">
                    {{ $title }}
                </h2>
            @endif

            @if ($desc)
                <p class="text-base text-background/90 font-medium leading-relaxed">
                    {{ $desc }}
                </p>
            @endif
        </div>

        <div class="flex flex-wrap items-center justify-center gap-4 shrink-0">
            <x-frontend.button :href="$link" variant="secondary" size="lg">
                {{ __('frontend.contact_us_now') }}
            </x-frontend.button>
            <x-frontend.button :href="route('about.index')" variant="outline" size="lg"
                class="border-background text-background hover:bg-background hover:text-primary">
                {{ __('frontend.know_our_impact') }}
            </x-frontend.button>
        </div>
    </div>
</section>
