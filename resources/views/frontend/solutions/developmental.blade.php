@php
    $locale = app()->getLocale();
    $bgUrl  = asset('storage/backgroundSolution/' . str_replace(' ', '%20', 'solution .JPG.jpeg'));
@endphp

<x-frontend-layout title="{{ $locale === 'ar' ? 'الحلول التنموية' : 'Developmental Solutions' }}">

    {{-- ════════════════════════════════════════════════════════════════
         HERO — Full-width, background image, warm institutional overlay
    ════════════════════════════════════════════════════════════════ --}}
    <x-slot:hero>
        <div class="relative w-full overflow-hidden" style="min-height: 360px;">

            {{-- Background Image --}}
            <div class="absolute inset-0 bg-cover bg-center bg-no-repeat"
                 style="background-image: url('{{ $bgUrl }}');"></div>

            {{-- Warm institutional green overlay --}}
            <div class="absolute inset-0 bg-gradient-to-b from-[#3D342A]/80 via-[#A38B54]/65 to-[#3D342A]/85"></div>

            {{-- Subtle radial dot texture for depth --}}
            <div class="absolute inset-0 opacity-[0.05]"
                 style="background-image: radial-gradient(circle, white 1px, transparent 1px); background-size: 28px 28px;"></div>

            {{-- Hero Content --}}
            <div class="relative z-10 flex flex-col items-center justify-center px-4 text-center"
                 style="min-height: 360px; padding-top: 5rem; padding-bottom: 4rem;">

                {{-- Back Navigation --}}
                <div class="absolute top-5 start-5 sm:start-8">
                    <a href="{{ route('solutions.index') }}"
                       class="inline-flex items-center gap-1.5 text-white/75 hover:text-white text-sm font-medium
                              transition-colors duration-200 rounded-lg px-2 py-1
                              focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#EAEAE9] focus-visible:ring-offset-1 focus-visible:ring-offset-transparent">
                        <svg class="w-4 h-4 {{ $locale === 'ar' ? 'rotate-180' : '' }} shrink-0"
                             fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        <span>{{ __('frontend.back_to_solutions') }}</span>
                    </a>
                </div>

                {{-- Badge --}}
                <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full text-xs font-semibold
                             tracking-widest uppercase bg-[#EAEAE9]/15 text-[#EAEAE9]
                             border border-[#EAEAE9]/25 backdrop-blur-sm mb-5">
                    <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    {{ $locale === 'ar' ? 'الحلول التنموية' : 'Developmental Solutions' }}
                </span>

                {{-- Page Title --}}
                <h1 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-white leading-tight max-w-3xl mx-auto">
                    {{ $locale === 'ar'
                        ? 'الحلول التنموية المتكاملة'
                        : 'Integrated Developmental Solutions' }}
                </h1>

                {{-- Description --}}
                <p class="mt-4 text-white/80 text-base sm:text-lg max-w-2xl mx-auto leading-relaxed">
                    {{ $locale === 'ar'
                        ? 'باقة من الحلول التنموية المتكاملة التي تدعم بناء القدرات وتنمية المجتمعات والابتكار الاجتماعي.'
                        : 'A suite of integrated developmental solutions supporting capacity building, community development, and social innovation.' }}
                </p>

                {{-- Meta indicators --}}
                <div class="mt-7 flex items-center justify-center gap-6 flex-wrap">
                    <div class="flex items-center gap-2 text-white/65 text-sm">
                        <svg class="w-4 h-4 text-[#EAEAE9] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span>{{ $locale === 'ar' ? '٧ حلول تنموية' : '7 Developmental Solutions' }}</span>
                    </div>
                    <div class="hidden sm:block w-px h-4 bg-white/20"></div>
                    <div class="flex items-center gap-2 text-white/65 text-sm">
                        <svg class="w-4 h-4 text-[#EAEAE9] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064"/>
                        </svg>
                        <span>{{ $locale === 'ar' ? 'أثر مجتمعي مستدام' : 'Sustainable Community Impact' }}</span>
                    </div>
                    <div class="hidden sm:block w-px h-4 bg-white/20"></div>
                    <div class="flex items-center gap-2 text-white/65 text-sm">
                        <svg class="w-4 h-4 text-[#EAEAE9] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                        <span>{{ $locale === 'ar' ? 'بناء القدرات' : 'Capacity Building' }}</span>
                    </div>
                </div>
            </div>

            {{-- Bottom fade to page background --}}
            <div class="absolute bottom-0 left-0 right-0 h-8 bg-gradient-to-t from-surface dark:from-gray-900 to-transparent pointer-events-none"></div>
        </div>
    </x-slot:hero>

    {{-- ════════════════════════════════════════════════════════════════
         SOLUTIONS GRID — 7 Self-Contained Cards
         No links to individual solution detail pages.
    ════════════════════════════════════════════════════════════════ --}}
    <div class="py-12 md:py-16">

        @if ($solutions->isNotEmpty())

            @php
                $iconsMap = [
                    'hlol-almoss-altnmoy' => 'award',
                    'hlol-tnmy-almgtmaaat' => 'building-2',
                    'hlol-bnaaa-alkyadat' => 'users',
                    'hlol-bnaaa-alkdrat' => 'graduation-cap',
                    'hlol-tsmym-almbadrat' => 'clipboard-list',
                    'hlol-alabtkar-alagtmaaay' => 'lightbulb',
                    'hlol-almaarf-oaltnmy' => 'book-open',
                ];
            @endphp

            <x-frontend.solutions-timeline :solutions="$solutions" :iconsMap="$iconsMap" />

        @else
            <x-frontend.empty-state
                :title="__('frontend.no_solutions_available')"
                :description="__('frontend.solutions_coming_soon')"
            />
        @endif

    </div>

</x-frontend-layout>
