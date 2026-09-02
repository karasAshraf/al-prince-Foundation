@props([
    'partners',
    'index' => 5,
])

@if($partners && $partners->isNotEmpty())
    <x-frontend.section 
        :index="$index"
        align="center"
        x-data="{ shown: false }"
        x-intersect.once="shown = true"
        class="border-t border-secondary/30 overflow-hidden pb-12 lg:pb-20"
    >
        <x-frontend.section-title
            title="{{ __('frontend.partners_title') }}"
            description="{{ __('frontend.partners_description') }}"
            align="center"
            class="transition-all duration-700 transform"
            ::class="shown ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-8'"
        >
            <x-slot:badgeSlot>
                <x-frontend.badge class="!bg-primary/10 !text-primary border border-primary/20">
                    {{ __('frontend.partners_badge') }}
                </x-frontend.badge>
            </x-slot:badgeSlot>
        </x-frontend.section-title>

        <!-- Partners Carousel / Grid Container -->
        <div class="relative w-full overflow-hidden py-4 select-none transition-all duration-1000 delay-200 transform"
             ::class="shown ? 'opacity-100 scale-100' : 'opacity-0 scale-95'">
            
            <!-- Fade overlays for seamless scrolling edges — RTL mirrored via CSS in <style> block below -->
            <div class="partners-fade-start absolute start-0 top-0 bottom-0 w-12 md:w-28 z-10 pointer-events-none"></div>
            <div class="partners-fade-end absolute end-0 top-0 bottom-0 w-12 md:w-28 z-10 pointer-events-none"></div>

            <!-- Scrolling conveyor -->
            <div class="flex w-max items-stretch gap-5 md:gap-6 py-4 partners-marquee-track hover:[animation-play-state:paused]"
                 style="animation-play-state: running;">
                
                {{-- Render logos list twice to allow seamless infinite loop --}}
                @foreach([1, 2] as $loopIndex)
                    <div class="flex items-stretch gap-5 md:gap-6 shrink-0">
                        @foreach($partners as $partner)
                            @php
                                $logoUrl = \App\Helpers\MediaHelper::url($partner, 'partner_logos', 'image', 'card');
                                $partnerName = $partner->localizedName();
                                $hasLink = !empty($partner->external_link);
                            @endphp

                            <{{ $hasLink ? 'a' : 'div' }}
                                @if($hasLink)
                                    href="{{ $partner->external_link }}"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                @endif
                                class="group flex flex-col items-center justify-between bg-background dark:bg-gray-800/90 border border-secondary dark:border-gray-700/60 rounded-2xl p-5 w-[220px] h-[190px] shadow-sm hover:border-secondary hover:shadow-md transition-all duration-300 transform hover:-translate-y-1 text-center"
                                title="{{ $partnerName }}">
                                
                                <!-- Logo Box -->
                                <div class="w-full h-[100px] flex items-center justify-center p-1">
                                    @if($logoUrl)
                                        <img src="{{ $logoUrl }}" 
                                             alt="{{ $partnerName }}" 
                                             loading="lazy"
                                             class="w-full h-full object-contain transition-transform duration-300 group-hover:scale-105">
                                    @else
                                        <div class="w-14 h-14 rounded-full bg-secondary/10 text-secondary flex items-center justify-center font-bold text-xl">
                                            {{ mb_substr($partnerName, 0, 1) }}
                                        </div>
                                    @endif
                                </div>

                                <!-- Partner Name -->
                                <div class="w-full pt-4 mt-2 border-t border-secondary/40 dark:border-gray-700/50">
                                    <h4 class="text-[13px] font-medium text-text-primary dark:text-gray-300 transition-colors duration-300 line-clamp-2 leading-snug">
                                        {{ $partnerName }}
                                    </h4>
                                </div>
                            </{{ $hasLink ? 'a' : 'div' }}>
                        @endforeach
                    </div>
                @endforeach
                
            </div>
        </div>

        <style>
            .partners-marquee-track {
                display: flex;
                animation: partners-marquee-ltr 30s linear infinite;
            }
            [dir="rtl"] .partners-marquee-track {
                animation: partners-marquee-rtl 30s linear infinite;
            }

            @keyframes partners-marquee-ltr {
                0% { transform: translateX(0); }
                100% { transform: translateX(calc(-50% - 10px)); }
            }
            @keyframes partners-marquee-rtl {
                0% { transform: translateX(0); }
                100% { transform: translateX(calc(50% + 10px)); }
            }

            /* RTL-aware fade overlays */
            .partners-fade-start {
                background: linear-gradient(to right, #F5F5F5, transparent);
            }
            .partners-fade-end {
                background: linear-gradient(to left, #F5F5F5, transparent);
            }
            [dir="rtl"] .partners-fade-start {
                background: linear-gradient(to left, #F5F5F5, transparent);
            }
            [dir="rtl"] .partners-fade-end {
                background: linear-gradient(to right, #F5F5F5, transparent);
            }
            @media (prefers-color-scheme: dark) {
                .partners-fade-start, .partners-fade-end { background: none; }
            }
            .dark .partners-fade-start {
                background: linear-gradient(to right, #111827, transparent);
            }
            .dark .partners-fade-end {
                background: linear-gradient(to left, #111827, transparent);
            }
            [dir="rtl"] .dark .partners-fade-start {
                background: linear-gradient(to left, #111827, transparent);
            }
            [dir="rtl"] .dark .partners-fade-end {
                background: linear-gradient(to right, #111827, transparent);
            }
        </style>
    </x-frontend.section>
@endif

