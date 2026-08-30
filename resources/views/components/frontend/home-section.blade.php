@props([
    'section',
    'index'   => 0,
    'noBadge' => false,
])

@php
    $locale    = app()->getLocale();
    $title     = $locale === 'ar' ? $section->title_ar : ($section->title_en ?? $section->title_ar);
    $desc      = $locale === 'ar' ? $section->description_ar : ($section->description_en ?? $section->description_ar);
    $img       = \App\Helpers\MediaHelper::url($section, 'home_section_images', 'image', 'detail');
    $link      = $section->extra_link;
    $isReverse = ($index % 2) === 1;

    /*
     * DISPLAY-ONLY: Split the plain-text "details" field into a special first line
     * (the Bismillah phrase) and the body text that follows it.
     *
     * Convention: Editors may store "بسم الله الرحمن الرحيم" either:
     *   1) on its own first line, separated by a newline, OR
     *   2) directly followed by the body text with no newline at all.
     *
     * We detect the phrase by locating it directly (mb_strpos, UTF-8 safe) rather
     * than relying on the presence of a newline, so both authoring styles work.
     *
     * Guard: the phrase must appear at the very start of the text (allowing only
     * leading whitespace before it). This prevents accidentally splitting content
     * where the phrase appears mid-paragraph, and keeps English content (which
     * never contains this phrase) on the normal rendering path untouched.
     *
     * No database content is modified. This is pure presentation logic.
     */
    /*
     * We match multiple opening phrases (Arabic with both spellings, English)
     * because editors may store slightly different orthography.
     * Detection is position-based: the phrase must appear at/near the very
     * start of the stored text (allowing only leading whitespace before it).
     */
    $bismillahVariants = [
        'بسم الله الرحمن الرحيم',   // standard Arabic
        'بسم اللة الرحمن الرحيم',   // alternate / mis-typed Arabic (اللة)
        'بسم اللّه الرحمن الرحيم',  // Arabic with shadda on lam
        'In the name of God, the Most Gracious, the Most Merciful.', // English
        'In the name of Allah, the Most Gracious, the Most Merciful.', // English alt
    ];

    $bismillahFirstLine = null;
    $descBody           = $desc;

    if (!empty($desc)) {
        foreach ($bismillahVariants as $phrase) {
            $pos = mb_strpos($desc, $phrase);
            if ($pos !== false && trim(mb_substr($desc, 0, $pos)) === '') {
                $bismillahFirstLine = $phrase;
                $descBody           = ltrim(mb_substr($desc, $pos + mb_strlen($phrase)), "\r\n ");
                break;
            }
        }
    }
@endphp

@if ($title || $desc)
<x-frontend.section 
    :index="$index"
    :align="$img ? 'start' : 'center'"
    x-data="{ inView: false }"
    x-intersect.once="inView = true"
    class="relative overflow-hidden"
>
    <div class="grid grid-cols-1 {{ $img ? 'lg:grid-cols-12' : '' }} gap-12 lg:gap-16 items-center">

        {{-- Image Column (Alternates side automatically based on loop index) --}}
        @if ($img)
            <div
                :class="inView ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-10'"
                class="{{ $isReverse ? 'lg:col-span-6 lg:order-2' : 'lg:col-span-6 lg:order-1' }} relative group transition-all duration-700 ease-out"
            >
                {{-- Decorative Floating Glow Behind Image --}}
                <div class="absolute -inset-4 rounded-3xl bg-gradient-to-tr from-primary-light/10 to-primary/10 blur-2xl opacity-40 group-hover:opacity-80 transition-opacity duration-500 pointer-events-none"></div>

                <div class="relative overflow-hidden rounded-3xl border border-border dark:border-gray-800/40 shadow-md group-hover:shadow-lg transition-all duration-500">
                    <img
                        src="{{ $img }}"
                        alt="{{ $title ?? '' }}"
                        loading="lazy"
                        class="w-full h-[360px] sm:h-[440px] object-cover object-center transform group-hover:scale-105 transition-transform duration-700 ease-out"
                    />
                </div>

                {{-- Person Name: Displayed directly below the photo --}}
                @php
                    $personName = $locale === 'ar' ? $section->person_name_ar : ($section->person_name_en ?? $section->person_name_ar);
                @endphp
                @if (!empty($personName))
                    <div class="mt-4 text-center">
                        <span class="inline-block text-sm sm:text-base font-semibold text-text-primary dark:text-gray-200 bg-primary-light/10 dark:bg-gray-800/60 px-4 py-1.5 rounded-full border border-primary-light/20 dark:border-gray-700/40">
                            {{ $personName }}
                        </span>
                    </div>
                @endif
            </div>
        @endif

        {{-- Content Column --}}
        <div
            :class="inView ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-10'"
            class="{{ $img ? ($isReverse ? 'lg:col-span-6 lg:order-1' : 'lg:col-span-6 lg:order-2') : 'max-w-3xl mx-auto text-center' }} space-y-6 transition-all duration-700 ease-out delay-100"
        >
            @if ($section->label)
                <div class="inline-flex items-center gap-2">
                    <x-frontend.badge variant="secondary" size="md" class="bg-primary/10 text-primary dark:bg-primary-light/20 dark:text-primary-light border border-primary-light/30 px-4 py-1.5 rounded-full font-bold">
                        {{ $section->label }}
                    </x-frontend.badge>
                </div>
            @endif

            @if ($title)
                <h2 class="text-3xl sm:text-4xl font-bold text-text-primary dark:text-white leading-tight tracking-tight">
                    {{ $title }}
                </h2>
            @endif

            @if ($desc)
                {{-- Bismillah first-line (Arabic only) — visually distinct, centered, semibold --}}
                @if ($bismillahFirstLine)
                    <p class="w-full text-center text-base sm:text-lg font-semibold text-primary dark:text-primary-light tracking-wide mb-5 sm:mb-6 pb-4 border-b border-primary/15" dir="rtl">
                        {{ $bismillahFirstLine }}
                    </p>
                @endif

                {{-- Body text: flows naturally by container width --}}
                @if ($descBody)
                    <p class="text-base sm:text-lg text-text-secondary dark:text-gray-300 leading-relaxed font-normal max-w-prose">
                        {{ $descBody }}
                    </p>
                @endif
            @endif

            @if ($link && \App\Helpers\MediaHelper::shouldShowExternalLink($section, $link, 'home_section_images', 'image'))
                <div class="pt-4">
                    <x-frontend.button :href="$link" variant="primary" size="lg" class="shadow-lg hover:-translate-y-1 hover:shadow-xl transition-all duration-300 rounded-2xl px-8 py-3.5 font-bold">
                        {{ __('frontend.discover_more') }}
                        <svg class="w-5 h-5 rtl:rotate-180 inline-block ms-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                        </svg>
                    </x-frontend.button>
                </div>
            @endif
        </div>

    </div>
</x-frontend.section>
@endif