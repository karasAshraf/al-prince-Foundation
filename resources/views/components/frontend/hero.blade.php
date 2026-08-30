@props([
    'slides'  => null,
    'section' => null,
    'placement' => null,
])

@php
    $locale = app()->getLocale();

    // 1. Resolve slideCollection
    if (!empty($placement)) {
        $slideCollection = \App\Models\HeroSlide::where('placement', $placement)
            ->where('is_active', true)
            ->orderBy('order')
            ->get();
    } elseif ($slides instanceof \Illuminate\Support\Collection && $slides->isNotEmpty()) {
        $slideCollection = $slides;
    } elseif ($slides instanceof \Illuminate\Database\Eloquent\Model) {
        $slideCollection = collect([$slides]);
    } elseif ($section instanceof \Illuminate\Database\Eloquent\Model) {
        $slideCollection = collect([$section]);
    } else {
        $slideCollection = collect([]);
    }

    // 2. Map and Normalize properties for unified access
    $normalizedSlides = $slideCollection->map(function ($item) use ($locale) {
        if ($item instanceof \App\Models\HeroSlide) {
            return (object)[
                'title' => $locale === 'ar' ? $item->title_ar : ($item->title_en ?? $item->title_ar),
                'desc' => $locale === 'ar' ? $item->subtitle_ar : ($item->subtitle_en ?? $item->subtitle_ar),
                'link' => $item->button_url,
                'link_text' => $locale === 'ar' ? ($item->button_text_ar ?: __('frontend.discover_more')) : ($item->button_text_en ?: ($item->button_text_ar ?: __('frontend.discover_more'))),
                'imageUrl' => \App\Helpers\MediaHelper::url($item, 'hero_slide_images', 'image', 'hero'),
                'isExternal' => !empty($item->button_url),
                'raw_model' => $item,
            ];
        } else {
            // HomePageSection
            $link = $item->extra_link;
            return (object)[
                'title' => $locale === 'ar' ? $item->title_ar : ($item->title_en ?? $item->title_ar),
                'desc' => $locale === 'ar' ? $item->description_ar : ($item->description_en ?? $item->description_ar),
                'link' => $link,
                'link_text' => $link ? __('frontend.discover_more') : __('frontend.explore_our_projects'),
                'imageUrl' => \App\Helpers\MediaHelper::url($item, 'home_section_images', 'image', 'hero'),
                'isExternal' => $link && \App\Helpers\MediaHelper::shouldShowExternalLink($item, $link, 'home_section_images', 'image'),
                'raw_model' => $item,
            ];
        }
    });

    $slideCount = $normalizedSlides->count();
@endphp

<section class="relative w-full h-[70vh] sm:h-[75vh] md:h-[82vh] min-h-[500px] md:min-h-[600px] flex items-center justify-center overflow-hidden bg-primary"
         x-data="{
             activeSlide: 0,
             totalSlides: {{ $slideCount > 0 ? $slideCount : 1 }},
             isPaused: false,
             autoplayTimer: null,
             mounted: false,
             next() {
                 this.activeSlide = (this.activeSlide + 1) % this.totalSlides;
             },
             prev() {
                 this.activeSlide = (this.activeSlide - 1 + this.totalSlides) % this.totalSlides;
             },
             goTo(index) {
                 this.activeSlide = index;
             },
             startAutoplay() {
                 if (this.totalSlides > 1) {
                     this.autoplayTimer = setInterval(() => {
                         if (!this.isPaused) this.next();
                     }, 6000);
                 }
             },
             stopAutoplay() {
                 if (this.autoplayTimer) clearInterval(this.autoplayTimer);
             }
         }"
         x-init="startAutoplay(); $nextTick(() => { mounted = true; })"
         @mouseenter="isPaused = true"
         @mouseleave="isPaused = false"
         @keydown.arrow-right.window="document.dir === 'rtl' ? prev() : next()"
         @keydown.arrow-left.window="document.dir === 'rtl' ? next() : prev()"
         aria-label="{{ __('frontend.hero_carousel') }}">

    @forelse ($normalizedSlides as $index => $slide)
        @php
            $mediaUrl = $slide->imageUrl;
            $isVideo = false;
            $isEmbed = false;
            $embedUrl = null;

            if ($mediaUrl) {
                if (!\App\Helpers\MediaHelper::isImageUrl($mediaUrl)) {
                    $isVideo = true;
                    // Check if it's youtube or vimeo to get embed URL
                    if (preg_match('/(?:youtu\.be\/|youtube\.com\/(?:embed\/|v\/|watch\?v=|watch\?.+&v=))([\w-]{11})/i', $mediaUrl, $matches)) {
                        $isEmbed = tru
                        
                        e;
                        $embedUrl = 'https://www.youtube.com/embed/' . $matches[1] . '?autoplay=1&mute=1&controls=0&loop=1&playlist=' . $matches[1] . '&showinfo=0&rel=0';
                    } elseif (preg_match('/vimeo\.com\/(?:video\/)?(\d+)/i', $mediaUrl, $matches)) {
                        $isEmbed = true;
                        $embedUrl = 'https://player.vimeo.com/video/' . $matches[1] . '?autoplay=1&loop=1&muted=1&background=1';
                    }
                }
            }

            if ($index === 0 && $mediaUrl && !$isVideo) {
                View::startPush('preload', '<link rel="preload" as="image" href="' . e($mediaUrl) . '" fetchpriority="high">' . PHP_EOL);
            }
        @endphp

        <div x-show="activeSlide === {{ $index }}"
             @if($index > 0) x-cloak @else style="display:block" @endif
             x-transition:enter="transition opacity duration-500 ease-in-out"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition opacity duration-300 ease-in-out"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="absolute inset-0 w-full h-full flex items-center justify-center group overflow-hidden">

            <!-- Media Background (Image, Video, or Embed) -->
            @if ($mediaUrl)
                @if ($isVideo)
                    @if ($isEmbed)
                        <iframe src="{{ $embedUrl }}" class="absolute inset-0 w-full h-full object-cover object-center pointer-events-none scale-105" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
                    @else
                        <video src="{{ $mediaUrl }}" autoplay loop muted playsinline class="absolute inset-0 w-full h-full object-cover object-center"></video>
                    @endif
                @else
                    <img src="{{ $mediaUrl }}"
                         alt="{{ $slide->title ?? '' }}"
                         loading="{{ $index === 0 ? 'eager' : 'lazy' }}"
                         @if ($index === 0) fetchpriority="high" @endif
                         :class="activeSlide === {{ $index }} ? 'animate-kenburns' : ''"
                         class="absolute inset-0 w-full h-full object-cover object-center transform transition-transform duration-[6000ms] ease-out">
                @endif
            @else
                <!-- Fallback Gradient Background -->
                <div class="absolute inset-0 w-full h-full bg-gradient-to-br from-primary via-emerald-950 to-gray-950"></div>
            @endif

            <!-- On-brand duotone overlay for readability -->
            <div class="absolute inset-0 bg-gradient-to-t from-primary/85 via-primary/20 to-transparent" aria-hidden="true"></div>

            <!-- Content Container -->
            <x-frontend.container class="relative z-10 py-12 text-center text-white h-full flex flex-col justify-between">
                <!-- Empty top spacer to keep title/desc vertically aligned towards center-top without collision -->
                <div class="flex-grow flex items-center justify-center">
                    <div class="max-w-4xl mx-auto space-y-4 md:space-y-6">
                        <!-- Main H1 Headline -->
                        @if ($slide->title)
                            <h1 class="text-2xl sm:text-4xl md:text-5xl lg:text-6xl font-bold text-white leading-tight tracking-tight drop-shadow-md max-w-xl mx-auto">
                                {{ $slide->title }}
                            </h1>
                        @endif

                        <!-- Subtitle / Description -->
                        @if ($slide->desc)
                            <p class="text-xs sm:text-sm md:text-base lg:text-lg text-gray-100 font-medium max-w-xl mx-auto leading-relaxed drop-shadow break-words line-clamp-3 sm:line-clamp-none">
                                {{ $slide->desc }}
                            </p>
                        @endif
                    </div>
                </div>

                <!-- Action CTA Button placed at the bottom area of the slide, centered, above indicators/dots -->
                <div class="pb-16 sm:pb-20 flex items-center justify-center">
                    @if ($slide->link)
                        <x-frontend.button :href="$slide->link" variant="secondary" size="lg" class="px-8 py-3.5 font-bold group shadow-md transition-all duration-200 rounded-xl border border-primary-light/30 hover:bg-[#e2e78c] active:bg-[#d5da78] focus-visible:ring-2 focus-visible:ring-secondary-light focus-visible:ring-offset-2">
                            <span>{{ __('frontend.explore_our_projects') }}</span>
                            <svg class="w-5 h-5 rtl:rotate-180 inline-block ms-2 transform group-hover:translate-x-1 rtl:group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                            </svg>
                        </x-frontend.button>
                    @else
                        <x-frontend.button :href="route('projects.index')" variant="secondary" size="lg" class="px-8 py-3.5 font-bold group shadow-md transition-all duration-200 rounded-xl border border-primary-light/30 hover:bg-[#e2e78c] active:bg-[#d5da78] focus-visible:ring-2 focus-visible:ring-secondary-light focus-visible:ring-offset-2">
                            <span>{{ __('frontend.explore_our_projects') }}</span>
                            <svg class="w-5 h-5 rtl:rotate-180 inline-block ms-2 transform group-hover:translate-x-1 rtl:group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                            </svg>
                        </x-frontend.button>
                    @endif
                </div>
            </x-frontend.container>
        </div>
    @empty
        <!-- Default Fallback Hero Slide -->
        <div class="absolute inset-0 w-full h-full bg-gradient-to-br from-primary via-emerald-950 to-gray-950 flex items-center justify-center">
            <div class="absolute inset-0 bg-black/65" aria-hidden="true"></div>
            <x-frontend.container class="relative z-10 text-center text-white py-16">
                <div class="max-w-3xl mx-auto space-y-6">
                    <x-frontend.badge variant="secondary" size="md">
                        {{ __('frontend.brand_name') }}
                    </x-frontend.badge>
                    <h1 class="text-2xl sm:text-5xl font-bold text-white leading-tight max-w-xl mx-auto">
                        {{ __('frontend.brand_tagline') }}
                    </h1>
                    <p class="text-xs sm:text-base text-gray-200 leading-relaxed break-words max-w-xl mx-auto">
                        {{ __('frontend.brand_description') }}
                    </p>
                </div>
            </x-frontend.container>
        </div>
    @endforelse

    <!-- Navigation Arrows -->
    @if ($slideCount > 1)
        <!-- Previous Slide Button -->
        <button @click="prev()"
                type="button"
                class="absolute top-1/2 -translate-y-1/2 start-2 sm:start-4 md:start-8 z-20 w-10 h-10 sm:w-12 sm:h-12 flex items-center justify-center rounded-full text-white/90 bg-white/20 backdrop-blur-md hover:bg-white hover:text-text-primary transition-all duration-300 focus:outline-none focus-visible:ring-2 focus-visible:ring-primary-light group"
                aria-label="{{ __('frontend.previous_slide') }}">
            <svg class="w-6 h-6 rtl:rotate-180 group-hover:-translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/>
            </svg>
        </button>

        <!-- Next Slide Button -->
        <button @click="next()"
                type="button"
                class="absolute top-1/2 -translate-y-1/2 end-2 sm:end-4 md:end-8 z-20 w-10 h-10 sm:w-12 sm:h-12 flex items-center justify-center rounded-full text-white/90 bg-white/20 backdrop-blur-md hover:bg-white hover:text-text-primary transition-all duration-300 focus:outline-none focus-visible:ring-2 focus-visible:ring-primary-light group"
                aria-label="{{ __('frontend.next_slide') }}">
            <svg class="w-6 h-6 rtl:rotate-180 group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
            </svg>
        </button>

        <!-- Pagination Dots Bar -->
        <div class="absolute bottom-6 left-1/2 -translate-x-1/2 z-20 flex items-center gap-2 p-1.5 rounded-full bg-black/20 backdrop-blur-sm">
            @foreach ($slideCollection as $index => $s)
                <button @click="goTo({{ $index }})"
                        type="button"
                        class="p-1 focus:outline-none focus-visible:ring-2 focus-visible:ring-primary-light rounded-full"
                        :aria-current="activeSlide === {{ $index }} ? 'true' : 'false'"
                        aria-label="{{ __('frontend.go_to_slide') }} {{ $index + 1 }}">
                    <span class="block h-2 rounded-full transition-all duration-300"
                          :class="activeSlide === {{ $index }} ? 'w-6 bg-primary' : 'w-2 bg-white/50 hover:bg-white/80'">
                          </span>
                </button>
            @endforeach
        </div>
    @endif
</section>
