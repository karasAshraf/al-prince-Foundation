{{-- ╔══════════════════════════════════════════════════════════════════╗ --}}
{{-- ║  Executive Team Page                                            ║ --}}
{{-- ║  Data: $executiveMembers (TeamMember collection, type=executive)║ --}}
{{-- ╚══════════════════════════════════════════════════════════════════╝ --}}

<x-frontend-layout title="{{ __('frontend.executive_team') }}">

    @php $isRtl = app()->getLocale() === 'ar'; @endphp

    {{-- ── Page Header ──────────────────────────────────────────────────── --}}
    <div class="relative text-center pt-8 pb-4 mb-12 sm:mb-16">

        {{-- Decorative background blob --}}
        <div
            aria-hidden="true"
            class="absolute inset-0 -z-10 flex items-center justify-center pointer-events-none"
        >
            <div class="w-72 h-72 sm:w-96 sm:h-96 rounded-full bg-gradient-to-br from-secondary-light/30 via-primary-light/25 to-transparent blur-3xl opacity-60"></div>
        </div>

        {{-- Eyebrow --}}
        <span class="text-xs sm:text-sm font-bold uppercase tracking-wider text-primary/80 dark:text-secondary/90 mb-3 block">
            {{ __('frontend.executive_structure') }}
        </span>

        {{-- Page title --}}
        <h1 class="text-3xl sm:text-4xl lg:text-5xl font-black text-text-primary dark:text-background leading-tight tracking-tight mb-4">
            {{ __('frontend.executive_team') }}
        </h1>

        {{-- Subtle decorative brand element - slightly more dynamic/human wavy/pill accent --}}
        <div class="flex items-center justify-center gap-1.5 mt-4 mb-6" aria-hidden="true">
            <div class="w-3 h-1.5 rounded-full bg-primary"></div>
            <div class="w-1.5 h-1.5 rounded-full bg-secondary"></div>
            <div class="w-6 h-1 rounded-full bg-secondary"></div>
        </div>

        {{-- Description --}}
        <p class="mt-4 text-base sm:text-lg text-text-primary/75 dark:text-gray-300 max-w-2xl mx-auto leading-relaxed">
            {{ __('frontend.executive_team_desc') }}
        </p>
    </div>

    {{-- ── Team Grid ────────────────────────────────────────────────────── --}}
    @if ($executiveMembers->isEmpty())

        {{-- Empty state --}}
        <x-frontend.empty-state
            :title="__('frontend.no_executive_members')"
            :description="__('frontend.content_coming_soon')"
        >
            <x-slot:action>
                <x-frontend.button
                    :href="route('about.index')"
                    variant="outline"
                    size="sm"
                >
                    {{ $isRtl ? '→' : '←' }} {{ __('frontend.back_to_about') }}
                </x-frontend.button>
            </x-slot:action>
        </x-frontend.empty-state>

    @else

        <section aria-label="{{ __('frontend.executive_team') }}">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 lg:gap-8">
                @foreach ($executiveMembers as $member)
                    <x-frontend.team-card :member="$member" layout="grid" />
                @endforeach
            </div>
        </section>

    @endif

    {{-- ── Back Link ────────────────────────────────────────────────────── --}}
    <div class="text-center mt-14">
        <x-frontend.button
            :href="route('about.index')"
            variant="ghost"
            size="md"
        >
            @if ($isRtl)
                <svg class="w-4 h-4 inline-block me-1 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
            @else
                <svg class="w-4 h-4 inline-block me-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
            @endif
            {{ __('frontend.back_to_about') }}
        </x-frontend.button>
    </div>

</x-frontend-layout>
