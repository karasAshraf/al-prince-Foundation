@props([
    'variant' => 'inner', // 'home' or 'inner'
    'placement' => null,
    'title' => null,
    'subtitle' => null,
    'breadcrumbs' => null,
    'backUrl' => null,
    'backLabel' => null,
])

@php
    $locale = app()->getLocale();

    $dbSlides = collect();
    if (!empty($placement)) {
        $dbSlides = \App\Models\HeroSlide::active()
            ->where('placement', $placement)
            ->with('media')
            ->orderBy('order', 'asc')
            ->get();
    }

    $slides = $dbSlides->map(function ($item) use ($locale) {
        return (object)[
            'title' => ($locale === 'en' && !empty($item->title_en)) ? $item->title_en : $item->title_ar,
            'subtitle' => ($locale === 'en' && !empty($item->subtitle_en)) ? $item->subtitle_en : $item->subtitle_ar,
            'link' => $item->button_url,
            'link_text' => ($locale === 'en' && !empty($item->button_text_en)) ? $item->button_text_en : ($item->button_text_ar ?: ($locale === 'ar' ? 'اكتشف المزيد' : 'Discover More')),
            'imageUrl' => \App\Helpers\MediaHelper::url($item, 'hero_slide_images', 'image', 'hero'),
        ];
    });

    if ($slides->isEmpty()) {
        if (!empty($title)) {
            $slides = collect([
                (object)[
                    'title' => $title,
                    'subtitle' => $subtitle,
                    'link' => null,
                    'link_text' => null,
                    'imageUrl' => null,
                ]
            ]);
        } else {
            return;
        }
    }

    $slideCount = $slides->count();
@endphp

<section class="relative w-full overflow-hidden bg-primary flex items-center justify-center @if($variant === 'home') min-h-[600px] min-h-[100svh] sm:min-h-[600px] lg:h-[calc(100vh-6rem)] @else min-h-[300px] sm:min-h-[400px] lg:h-[40vh] @endif"
          x-data="{
             activeSlide: 0,
             totalSlides: {{ $slideCount > 0 ? $slideCount : 1 }},
             isPaused: false,
             autoplayTimer: null,
             mounted: false,
             next() { this.activeSlide = (this.activeSlide + 1) % this.totalSlides; },
             prev() { this.activeSlide = (this.activeSlide - 1 + this.totalSlides) % this.totalSlides; },
             goTo(index) { this.activeSlide = index; },
             startAutoplay() {
                 if (this.totalSlides > 1) {
                     this.autoplayTimer = setInterval(() => {
                         if (!this.isPaused) this.next();
                     }, 6000);
                 }
             },
             stopAutoplay() { if (this.autoplayTimer) clearInterval(this.autoplayTimer); }
         }"
         x-init="startAutoplay(); $nextTick(() => { mounted = true; })"
         @mouseenter="isPaused = true"
         @mouseleave="isPaused = false"
         @keydown.arrow-right.window="document.dir === 'rtl' ? prev() : next()"
         @keydown.arrow-left.window="document.dir === 'rtl' ? next() : prev()"
         aria-label="{{ __('frontend.hero_carousel') }}">

    {{-- ============ SLIDES (image + title + subtitle only) ============ --}}
    @foreach ($slides as $index => $slide)
        <div x-show="activeSlide === {{ $index }}"
             @if($index > 0) x-cloak @else style="display:block" @endif
             x-transition:enter="transition opacity duration-750 ease-in-out"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition opacity duration-750 ease-in-out"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="absolute inset-0 w-full h-full flex items-center justify-center group overflow-hidden">

            @if (!empty($slide->imageUrl))
                <img src="{{ $slide->imageUrl }}"
                     alt="{{ $slide->title ?? '' }}"
                     loading="{{ $index === 0 ? 'eager' : 'lazy' }}"
                     @if ($index === 0) fetchpriority="high" @endif
                     :class="activeSlide === {{ $index }} ? 'animate-kenburns' : ''"
                     class="absolute inset-0 w-full h-full object-cover object-center transform transition-transform duration-[6000ms] ease-out">
            @else
                <div class="absolute inset-0 w-full h-full bg-gradient-to-br from-primary via-emerald-950 to-gray-950"></div>
            @endif

            <div class="absolute inset-0 bg-gradient-to-t from-primary/85 via-primary/20 to-transparent" aria-hidden="true"></div>

            <x-frontend.container class="relative z-10 py-12 text-center text-background h-full flex flex-col justify-center items-center">

                @if ($variant === 'inner' && $backUrl)
                    <div class="absolute top-5 start-5 sm:start-8">
                        <a href="{{ $backUrl }}"
                           class="inline-flex items-center gap-1.5 text-background/75 hover:text-background text-sm font-medium transition-colors duration-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-secondary rounded px-1">
                            <svg class="w-4 h-4 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                            <span>{{ $backLabel ?: ($locale === 'ar' ? 'العودة' : 'Back') }}</span>
                        </a>
                    </div>
                @endif

                @php
                    $isOffWhiteHero = in_array($placement, ['news', 'industries', 'about', 'services']) || (isset($slide->title) && (str_contains($slide->title, 'أثر ملموس') || str_contains($slide->title, 'خدمات')));
                    $titleColorClass = $isOffWhiteHero ? 'text-[#F5F2EB]' : 'text-primary';
                    $subtitleColorClass = $isOffWhiteHero ? 'text-[#F5F2EB]/90' : 'text-[#AC8322]';
                @endphp

                <div class="max-w-4xl mx-auto flex flex-col items-center justify-center my-auto">
                    {{-- Floating typography directly over hero image without dark container box --}}
                    <div class="space-y-3 md:space-y-5 text-center">
                        @if ($slide->title)
                            <h1 class="@if($variant === 'home') text-2xl sm:text-4xl md:text-5xl lg:text-6xl @else text-xl sm:text-3xl md:text-4xl lg:text-5xl @endif font-extrabold {{ $titleColorClass }} leading-tight tracking-tight drop-shadow-md max-w-3xl mx-auto">
                                {{ $slide->title }}
                            </h1>
                        @endif

                        @if ($slide->subtitle)
                            <p class="@if($variant === 'home') text-xs sm:text-sm md:text-base lg:text-lg @else text-xs sm:text-sm md:text-base @endif {{ $subtitleColorClass }} font-semibold max-w-2xl mx-auto leading-relaxed break-words line-clamp-3 sm:line-clamp-none drop-shadow-md">
                                {{ $slide->subtitle }}
                            </p>
                        @endif
                    </div>
                </div>
                {{-- Center Content Box closed here — CTA no longer lives inside it --}}
            </x-frontend.container>
        </div>
    @endforeach
    {{-- ============ END SLIDES LOOP — everything below is OUTSIDE @foreach ============ --}}

    {{-- Bottom Stack: CTA Button + Pagination Dots --}}
    <div class="absolute inset-x-0 bottom-8 sm:bottom-12 z-20 flex flex-col items-center gap-5 sm:gap-6 px-4">

        @foreach ($slides as $index => $slide)
            @if ($slide->link)
                <div x-show="activeSlide === {{ $index }}"
                     @if($index > 0) x-cloak @endif
                     x-transition:enter="transition opacity duration-500 ease-in-out"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100">
                    <x-frontend.button :href="$slide->link" variant="secondary" size="lg" class="px-8 py-3.5 font-bold group shadow-md hover:shadow-lg hover:-translate-y-0.5 active:scale-95 transition-all duration-300 rounded-xl border border-secondary/30 hover:bg-background active:bg-background/80 focus-visible:ring-2 focus-visible:ring-secondary-light focus-visible:ring-offset-2">
                        <span>{{ $slide->link_text }}</span>
                        <svg class="w-5 h-5 rtl:rotate-180 inline-block ms-2 group-hover:translate-x-1 rtl:group-hover:-translate-x-1 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                        </svg>
                    </x-frontend.button>
                </div>
            @endif
        @endforeach

        @if ($slideCount > 1)
            <div class="flex items-center gap-2 p-1.5 rounded-full bg-black/40 backdrop-blur-sm"
                 :class="{ 'is-paused': isPaused }">
                @for ($i = 0; $i < $slideCount; $i++)
                    <button @click="goTo({{ $i }})"
                            type="button"
                            class="p-1 focus:outline-none focus-visible:ring-2 focus-visible:ring-primary-light rounded-full"
                            :aria-current="activeSlide === {{ $i }} ? 'true' : 'false'"
                            aria-label="{{ __('frontend.go_to_slide') }} {{ $i + 1 }}">
                        <span class="block h-2.5 rounded-full transition-all duration-300 relative overflow-hidden"
                              :class="activeSlide === {{ $i }} ? 'w-8 bg-background/25' : 'w-2.5 bg-background/50 hover:bg-background/70'">
                            <template x-if="activeSlide === {{ $i }}">
                                <span class="absolute inset-y-0 start-0 bg-background rounded-full active-progress-bar"></span>
                            </template>
                        </span>
                    </button>
                @endfor
            </div>
        @endif
    </div>

    {{-- Navigation Arrows --}}
    @if ($slideCount > 1)
        <button @click="prev()"
                type="button"
                class="absolute top-1/2 -translate-y-1/2 start-4 sm:start-6 md:start-8 z-20 w-11 h-11 sm:w-12 sm:h-12 flex items-center justify-center rounded-full text-background bg-background/20 backdrop-blur-md hover:bg-background/30 border border-background/10 hover:scale-105 active:scale-95 transition-all duration-300 focus:outline-none focus-visible:ring-2 focus-visible:ring-primary-light group"
                aria-label="{{ __('frontend.previous_slide') }}">
            <svg class="w-6 h-6 rtl:rotate-180 group-hover:-translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/>
            </svg>
        </button>

        <button @click="next()"
                type="button"
                class="absolute top-1/2 -translate-y-1/2 end-4 sm:end-6 md:end-8 z-20 w-11 h-11 sm:w-12 sm:h-12 flex items-center justify-center rounded-full text-background bg-background/20 backdrop-blur-md hover:bg-background/30 border border-background/10 hover:scale-105 active:scale-95 transition-all duration-300 focus:outline-none focus-visible:ring-2 focus-visible:ring-primary-light group"
                aria-label="{{ __('frontend.next_slide') }}">
            <svg class="w-6 h-6 rtl:rotate-180 group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
            </svg>
        </button>
    @endif

    <style>
        @keyframes hero-progress {
            0% { width: 0%; }
            100% { width: 100%; }
        }
        .active-progress-bar {
            animation: hero-progress 6000ms linear forwards;
        }
        .is-paused .active-progress-bar {
            animation-play-state: paused;
        }
    </style>

</section>