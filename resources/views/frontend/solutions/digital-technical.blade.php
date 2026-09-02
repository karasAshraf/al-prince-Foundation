@php
    $locale = app()->getLocale();
    $bgUrl  = asset('storage/backgroundSolution/' . str_replace(' ', '%20', 'solution .JPG.jpeg'));

    // Three distinct tech-themed icon paths (chip, analytics, network)
    $techIconPaths = [
        // Chip / Circuit board
        'M8.25 3v1.5M4.5 8.25H3m18 0h-1.5M4.5 12H3m18 0h-1.5m-15 3.75H3m18 0h-1.5M8.25 19.5V21M12 3v1.5m0 15V21m3.75-18v1.5m0 15V21m-9-1.5h10.5a2.25 2.25 0 002.25-2.25V6.75a2.25 2.25 0 00-2.25-2.25H6.75A2.25 2.25 0 004.5 6.75v10.5a2.25 2.25 0 002.25 2.25zm.75-12h9v9h-9v-9z',
        // Analytics / Bar chart
        'M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z',
        // Digital platform / Monitor
        'M9 17.25v1.007a3 3 0 01-.879 2.122L7.5 21h9l-.621-.621A3 3 0 0115 18.257V17.25m6-12V15a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 15V5.25m18 0A2.25 2.25 0 0018.75 3H5.25A2.25 2.25 0 003 5.25m18 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 7.409A2.25 2.25 0 012.25 5.493V5.25',
    ];
@endphp

<x-frontend-layout title="{{ $locale === 'ar' ? 'الحلول الرقمية والفنية' : 'Digital & Technical Solutions' }}">

    {{-- ════════════════════════════════════════════════════════════════
         HERO — Full-width, background image, dark tech overlay with grid
    ════════════════════════════════════════════════════════════════ --}}
    <x-slot:hero>
        <div class="relative w-full overflow-hidden" style="min-height: 360px;">

            {{-- Background Image --}}
            <div class="absolute inset-0 bg-cover bg-center bg-no-repeat"
                 style="background-image: url('{{ $bgUrl }}');"></div>

            {{-- Dark structured overlay (more cool/tech than the warm developmental overlay) --}}
            <div class="absolute inset-0 bg-gradient-to-br from-[#2A1F14]/88 via-[#3D342A]/80 to-[#5C5450]/75"></div>

            {{-- Subtle grid pattern — tech visual language --}}
            <div class="absolute inset-0 opacity-[0.07]"
                 style="background-image: linear-gradient(rgba(241,245,168,1) 1px, transparent 1px),
                                          linear-gradient(90deg, rgba(241,245,168,1) 1px, transparent 1px);
                        background-size: 44px 44px;"></div>

            {{-- Corner accent glow --}}
            <div class="absolute top-0 end-0 w-72 h-72 opacity-10 pointer-events-none"
                 style="background: radial-gradient(circle at top right, #EAEAE9, transparent 70%);"></div>

            {{-- Hero Content --}}
            <div class="relative z-10 flex flex-col items-center justify-center px-4 text-center"
                 style="min-height: 360px; padding-top: 5rem; padding-bottom: 4rem;">

                {{-- Back Navigation --}}
                <div class="absolute top-5 start-5 sm:start-8">
                    <a href="{{ route('solutions.index') }}"
                       class="inline-flex items-center gap-1.5 text-background/70 hover:text-background text-sm font-medium
                              transition-colors duration-200 rounded-lg px-2 py-1
                              focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-secondary focus-visible:ring-offset-1 focus-visible:ring-offset-transparent">
                        <svg class="w-4 h-4 rtl:rotate-180 shrink-0"
                             fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        <span>{{ __('frontend.back_to_solutions') }}</span>
                    </a>
                </div>

                {{-- Tech Badge --}}
                <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full text-xs font-semibold
                             tracking-widest uppercase bg-secondary/12 text-secondary
                             border border-secondary/20 backdrop-blur-sm mb-5">
                    <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    {{ $locale === 'ar' ? 'مركز الأمير عبد الرحمن' : 'Prince Abdulrahman Center' }}
                </span>

                {{-- Page Title --}}
                <h1 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-background leading-tight max-w-3xl mx-auto">
                    {{ $locale === 'ar'
                        ? 'الحلول الرقمية والفنية المتخصصة'
                        : 'Specialized Digital & Technical Solutions' }}
                </h1>

                {{-- Description --}}
                <p class="mt-4 text-background/78 text-base sm:text-lg max-w-2xl mx-auto leading-relaxed">
                    {{ $locale === 'ar'
                        ? 'يقدم مركز الأمير عبد الرحمن حلولاً ومنصات رقمية متكاملة وأطراً فنية لقياس وإدارة الأثر وتحسين كفاءة المبادرات.'
                        : 'Prince Abdulrahman Center provides integrated digital solutions, platforms, and technical frameworks to measure and manage impact and enhance initiative efficiency.' }}
                </p>

                {{-- Tech indicators --}}
                <div class="mt-7 flex items-center justify-center gap-6 flex-wrap">
                    <div class="flex items-center gap-2 text-background/60 text-sm font-mono">
                        <svg class="w-4 h-4 text-secondary shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M8.25 3v1.5M4.5 8.25H3m18 0h-1.5M4.5 12H3m18 0h-1.5m-15 3.75H3m18 0h-1.5M8.25 19.5V21M12 3v1.5m0 15V21m3.75-18v1.5m0 15V21m-9-1.5h10.5a2.25 2.25 0 002.25-2.25V6.75a2.25 2.25 0 00-2.25-2.25H6.75A2.25 2.25 0 004.5 6.75v10.5a2.25 2.25 0 002.25 2.25zm.75-12h9v9h-9v-9z"/>
                        </svg>
                        <span>{{ $locale === 'ar' ? '٣ حلول رقمية' : '3 Digital Solutions' }}</span>
                    </div>
                    <div class="hidden sm:block w-px h-4 bg-background/15"></div>
                    <div class="flex items-center gap-2 text-background/60 text-sm">
                        <svg class="w-4 h-4 text-secondary shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z"/>
                        </svg>
                        <span>{{ $locale === 'ar' ? 'قياس الأثر والتحليل' : 'Impact Measurement & Analytics' }}</span>
                    </div>
                    <div class="hidden sm:block w-px h-4 bg-background/15"></div>
                    <div class="flex items-center gap-2 text-background/60 text-sm">
                        <svg class="w-4 h-4 text-secondary shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                        <span>{{ $locale === 'ar' ? 'تحويل رقمي متكامل' : 'Integrated Digital Transformation' }}</span>
                    </div>
                </div>
            </div>

            {{-- Bottom fade to page background --}}
            <div class="absolute bottom-0 inset-x-0 h-8 bg-gradient-to-t from-surface dark:from-gray-900 to-transparent pointer-events-none"></div>
        </div>
    </x-slot:hero>

    {{-- ════════════════════════════════════════════════════════════════
         SOLUTIONS GRID — 3 Self-Contained Cards
         No links to individual solution detail pages.
    ════════════════════════════════════════════════════════════════ --}}
    <div class="py-12 md:py-16">

        @if ($solutions->isNotEmpty())

            <x-frontend.solutions-timeline :solutions="$solutions" :isTech="true" />

        @else
            <x-frontend.empty-state
                :title="__('frontend.no_solutions_available')"
                :description="__('frontend.solutions_coming_soon')"
            />
        @endif

    </div>

</x-frontend-layout>
