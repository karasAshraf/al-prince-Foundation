@props(['partners'])

@if($partners && $partners->isNotEmpty())
    <section class="py-16 md:py-20 bg-[#EAEAE9] dark:bg-gray-900 border-t border-primary-light/10 overflow-hidden"
             x-data="{ shown: false }"
             x-intersect.once="shown = true">
        <x-frontend.container>
            
            <x-frontend.section-title
                title="{{ __('frontend.partners_title') }}"
                description="{{ __('frontend.partners_description') }}"
                align="center"
                class="transition-all duration-700 transform"
                ::class="shown ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-8'"
            >
                <x-slot:badgeSlot>
                    <x-frontend.badge class="!bg-[#EAEAE9] !text-[#3D342A] border border-[#E2E886]">
                        {{ __('frontend.partners_badge') }}
                    </x-frontend.badge>
                </x-slot:badgeSlot>
            </x-frontend.section-title>

            <!-- Partners Carousel / Grid Container -->
            <div class="relative w-full overflow-hidden py-4 select-none transition-all duration-1000 delay-200 transform"
                 ::class="shown ? 'opacity-100 scale-100' : 'opacity-0 scale-95'">
                
                <!-- Fade overlays for seamless scrolling edges -->
                <div class="absolute left-0 top-0 bottom-0 w-12 md:w-28 bg-gradient-to-r from-[#EAEAE9] dark:from-gray-900 to-transparent z-10 pointer-events-none"></div>
                <div class="absolute right-0 top-0 bottom-0 w-12 md:w-28 bg-gradient-to-l from-[#EAEAE9] dark:from-gray-900 to-transparent z-10 pointer-events-none"></div>

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
                                    class="group flex flex-col items-center justify-between bg-white dark:bg-gray-800/90 border border-primary-light/20 dark:border-gray-700/60 rounded-2xl p-4 w-48 sm:w-52 md:w-56 h-36 sm:h-40 hover:border-primary/40 hover:shadow-lg hover:shadow-primary/5 transition-all duration-300 transform hover:-translate-y-1 text-center"
                                    title="{{ $partnerName }}">
                                    
                                    <!-- Logo Box -->
                                    <div class="flex-1 w-full flex items-center justify-center p-1.5 overflow-hidden">
                                        @if($logoUrl)
                                            <img src="{{ $logoUrl }}" 
                                                 alt="{{ $partnerName }}" 
                                                 loading="lazy"
                                                 class="max-h-16 md:max-h-20 w-auto max-w-full object-contain filter grayscale opacity-80 group-hover:grayscale-0 group-hover:opacity-100 transition-all duration-300 group-hover:scale-105">
                                        @else
                                            <div class="w-12 h-12 rounded-full bg-primary/10 text-primary flex items-center justify-center font-bold text-lg">
                                                {{ mb_substr($partnerName, 0, 1) }}
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Partner Name -->
                                    <div class="w-full pt-2 border-t border-gray-100 dark:border-gray-700/50 mt-1">
                                        <h4 class="text-xs sm:text-sm font-bold text-gray-700 dark:text-gray-200 group-hover:text-primary dark:group-hover:text-primary-light transition-colors duration-300 line-clamp-2 leading-tight">
                                            {{ $partnerName }}
                                        </h4>
                                    </div>
                                </{{ $hasLink ? 'a' : 'div' }}>
                            @endforeach
                        </div>
                    @endforeach
                    
                </div>
            </div>

        </x-frontend.container>

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
        </style>
    </section>
@endif

