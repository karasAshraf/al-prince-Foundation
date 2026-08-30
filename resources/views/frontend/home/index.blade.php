@php
    // Separate slider sections from the rest before rendering
    $heroSlides = $sections->filter(fn($s) => in_array($s->type, [
        \App\Models\HomePageSection::TYPE_SLIDER,
        \App\Models\HomePageSection::TYPE_HERO_SLIDER,
    ]));

    $bodySlides = $sections->reject(fn($s) => in_array($s->type, [
        \App\Models\HomePageSection::TYPE_SLIDER,
        \App\Models\HomePageSection::TYPE_HERO_SLIDER,
    ]));
@endphp

<x-frontend-layout title="{{ __('frontend.home') }}">

    {{-- ============================================================
         HERO SLOT — rendered full-width, OUTSIDE the container
         ============================================================ --}}
    <x-slot:hero>
        <x-hero-slider variant="home" placement="home" />
    </x-slot:hero>

    {{-- ============================================================
         DEFAULT SLOT — rendered inside max-w-7xl container
         ============================================================ --}}
    @forelse ($bodySlides as $section)
        @switch($section->type)

            @case(\App\Models\HomePageSection::TYPE_COUNTER)
            @case(\App\Models\HomePageSection::TYPE_COUNTERS)
                @php
                    $counterSections = $bodySlides->filter(fn($s) => in_array($s->type, [
                        \App\Models\HomePageSection::TYPE_COUNTER,
                        \App\Models\HomePageSection::TYPE_COUNTERS,
                    ]));
                @endphp
                {{-- Only render once; duplicates in loop are skipped --}}
                @if ($section->is($counterSections->first()))
                    <x-frontend.counter :sections="$counterSections" />
                @endif
                @break

            @case(\App\Models\HomePageSection::TYPE_LATEST_NEWS)
                <x-frontend.news-preview :news-items="$latestNews" :section="$section" />
                @break

            @case(\App\Models\HomePageSection::TYPE_SERVICE_SECTION)
                <x-frontend.services-preview :services="$services" :section="$section" />
                @break

            @case(\App\Models\HomePageSection::TYPE_ABOUT_PREVIEW)
            @case('about_preview')
                <x-frontend.about-preview :section="$section" />
                @break

            @case(\App\Models\HomePageSection::TYPE_PROJECTS_PREVIEW)
            @case('projects_preview')
                <x-frontend.projects-preview :section="$section" />
                @break

            @case(\App\Models\HomePageSection::TYPE_CTA)
            @case('cta')
                <x-frontend.cta :section="$section" />
                @break

            @default
                {{-- home_section: determine sub-intent from label field --}}
                @php $label = strtolower($section->label ?? ''); @endphp

                @if (str_contains($label, 'cta') || str_contains($label, 'call') || str_contains($label, 'action'))
                    <x-frontend.cta :section="$section" />
                @elseif (str_contains($label, 'about') || str_contains($label, 'عن'))
                    <x-frontend.about-preview :section="$section" />
                @elseif (str_contains($label, 'service') || str_contains($label, 'خدم'))
                    <x-frontend.services-preview :services="$services" :section="$section" />
                @elseif (str_contains($label, 'project') || str_contains($label, 'مشروع') || str_contains($label, 'مبادر'))
                    <x-frontend.projects-preview :section="$section" />
                @else
                    @php
                        /*
                         * Chairman's message detection: Bismillah phrase at the very start
                         * of the stored description (Arabic or English). We check the raw
                         * Arabic field first, then fall back to English.  This approach
                         * avoids hard-coding a database ID and works even if the section is
                         * duplicated or re-ordered.
                         */
                        $chairmanBismillahVariants = [
                            'بسم الله الرحمن الرحيم',
                            'بسم اللة الرحمن الرحيم',
                            'بسم اللّه الرحمن الرحيم',
                            'In the name of God, the Most Gracious, the Most Merciful.',
                            'In the name of Allah, the Most Gracious, the Most Merciful.',
                        ];
                        $rawDesc        = $section->description_ar ?? $section->description_en ?? '';
                        $isChairman     = false;
                        foreach ($chairmanBismillahVariants as $_bv) {
                            if (trim(mb_substr($rawDesc, 0, mb_strpos($rawDesc, $_bv) ?: 0)) === ''
                                && mb_strpos($rawDesc, $_bv) !== false
                                && mb_strpos($rawDesc, $_bv) === 0) {
                                $isChairman = true;
                                break;
                            }
                        }
                    @endphp
                    {{-- Generic home_section renderer with alternating index --}}
                    <x-frontend.home-section :section="$section" :index="$loop->index" :no-badge="$isChairman" />
                @endif

        @endswitch
    @empty
        {{-- Only show empty state when there are no sections at all (hero + body both empty) --}}
        @if ($sections->isEmpty())
            <x-frontend.empty-state
                title="{{ __('frontend.no_content_available') }}"
                description="{{ __('frontend.content_coming_soon') }}"
            />
        @endif
    @endforelse

    <x-frontend.partners-section :partners="$partners" />

</x-frontend-layout>
