@props([
    'section',
    'index' => 1,
])

@php
    $locale = app()->getLocale();
    $title  = $locale === 'ar' ? $section->title_ar : ($section->title_en ?? $section->title_ar);
    $desc   = $locale === 'ar' ? $section->description_ar : ($section->description_en ?? $section->description_ar);
    $img    = \App\Helpers\MediaHelper::url($section, 'about_images', 'image', 'card');

    /*
     * Break the description into readable paragraphs.
     * Split on double-newlines (editor paragraph breaks) or long single text.
     * Falls back to splitting by sentence if no natural breaks exist.
     */
    $paragraphs = [];
    if ($desc) {
        // Try splitting on double newlines first
        $chunks = preg_split('/\r?\n\r?\n/', trim($desc), -1, PREG_SPLIT_NO_EMPTY);
        if (count($chunks) >= 2) {
            $paragraphs = array_map('trim', $chunks);
        } else {
            // Single block — split into sentences (Arabic & English friendly)
            $sentences = preg_split('/(?<=[.!?؟،])\s+/', trim($desc), -1, PREG_SPLIT_NO_EMPTY);
            $groupSize = max(2, (int) ceil(count($sentences) / 3));
            $paragraphs = array_map(
                fn($chunk) => implode(' ', $chunk),
                array_chunk($sentences, $groupSize)
            );
        }
        $paragraphs = array_filter($paragraphs);
    }

    // Highlights extracted from the first non-empty description sentences for bullet list
    $highlights = [
        $locale === 'ar' ? 'تمكين المجتمعات وبناء القدرات' : 'Community Empowerment & Capacity Building',
        $locale === 'ar' ? 'تطوير الحلول التنموية المستدامة' : 'Sustainable Development Solutions',
        $locale === 'ar' ? 'تعزيز الشراكات وتحقيق الأثر المجتمعي' : 'Partnerships & Community Impact',
    ];
@endphp

<x-frontend.section
    :index="$index"
    align="start"
    x-data="{ inView: false }"
    x-intersect.once="inView = true"
>
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-16 items-center">

        {{-- ── Visual Column ─────────────────────────────────────── --}}
        <div class="lg:col-span-5 order-2 lg:order-1 transition-all duration-1000 transform"
             :class="inView ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-12'">

            @if ($img)
                {{-- Real photo from media library with gold overlay border --}}
                <div class="relative group">
                    {{-- Decorative gold glow behind image --}}
                    <div class="absolute -inset-3 rounded-3xl bg-gradient-to-tr from-primary/20 to-secondary/15 blur-xl opacity-60 group-hover:opacity-90 transition-opacity duration-500 pointer-events-none"></div>

                    <div class="relative overflow-hidden rounded-3xl border-2 border-primary/30 shadow-lg group-hover:shadow-xl group-hover:border-primary/50 transition-all duration-500">
                        <img
                            src="{{ $img }}"
                            alt="{{ $title ?? '' }}"
                            loading="lazy"
                            class="w-full h-[340px] sm:h-[400px] object-cover object-center transform group-hover:scale-105 transition-transform duration-700 ease-out"
                        />
                        {{-- Subtle gold gradient overlay at bottom --}}
                        <div class="absolute inset-x-0 bottom-0 h-16 bg-gradient-to-t from-text-primary/40 to-transparent pointer-events-none"></div>
                    </div>
                </div>
            @else
                {{-- OPTION B fallback: geometric pattern in approved palette — no illustrations, no green/blue --}}
                <div class="relative overflow-hidden rounded-3xl border-2 border-primary/30 bg-gradient-to-br from-text-primary to-[#4a3a2a] shadow-lg p-10 min-h-[300px] flex flex-col justify-between">
                    {{-- Decorative large circle --}}
                    <div class="absolute -top-12 -end-12 w-52 h-52 rounded-full bg-primary/10 pointer-events-none"></div>
                    <div class="absolute -bottom-8 -start-8 w-36 h-36 rounded-full bg-secondary/10 pointer-events-none"></div>

                    {{-- Foundation initial monogram --}}
                    <div class="relative z-10 w-16 h-16 rounded-2xl bg-primary flex items-center justify-center text-background font-bold text-3xl shadow-md">
                        {{ $locale === 'ar' ? 'م' : 'P' }}
                    </div>

                    {{-- Title inside card --}}
                    @if ($title)
                        <div class="relative z-10 mt-auto">
                            <p class="text-secondary text-xs font-semibold uppercase tracking-widest mb-2">
                                {{ $locale === 'ar' ? 'عن المؤسسة' : 'About Us' }}
                            </p>
                            <h3 class="text-xl font-bold text-background leading-snug">{{ $title }}</h3>
                        </div>
                    @endif

                    {{-- Decorative divider line --}}
                    <div class="relative z-10 mt-6 h-px w-16 bg-primary"></div>
                </div>
            @endif
        </div>

        {{-- ── Text Column ─────────────────────────────────────────── --}}
        <div class="lg:col-span-7 space-y-6 order-1 lg:order-2 transition-all duration-1000 transform delay-200"
             :class="inView ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-12'">

            {{-- Badge --}}
            <div class="inline-flex items-center gap-2">
                <span class="inline-block bg-primary text-background text-xs font-bold px-4 py-1.5 rounded-full tracking-wide">
                    {{ __('frontend.about_foundation') }}
                </span>
            </div>

            {{-- Heading --}}
            @if ($title)
                <h2 class="text-3xl sm:text-4xl font-bold text-text-primary leading-tight tracking-tight">
                    {{ $title }}
                </h2>
            @endif

            {{-- Description broken into readable paragraphs --}}
            @if (count($paragraphs))
                <div class="space-y-4">
                    @foreach ($paragraphs as $para)
                        <p class="text-base sm:text-lg text-text-primary leading-relaxed">
                            {{ $para }}
                        </p>
                    @endforeach
                </div>
            @endif

            {{-- Key highlights bullet list --}}
            <ul class="space-y-2.5 pt-1">
                @foreach ($highlights as $point)
                    <li class="flex items-start gap-3">
                        <span class="mt-1.5 w-2 h-2 rounded-full bg-primary shrink-0"></span>
                        <span class="text-sm sm:text-base text-text-primary font-medium">{{ $point }}</span>
                    </li>
                @endforeach
            </ul>

            {{-- CTA Button --}}
            <div class="pt-4">
                <x-frontend.button :href="route('about.index')" variant="primary">
                    {{ __('frontend.more_about_us') }}
                </x-frontend.button>
            </div>
        </div>
    </div>
</x-frontend.section>
