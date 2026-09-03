{{--
    Surveys index page — /surveys
    ─────────────────────────────────────────────────────────────────────────
    Hero: rendered automatically by the frontend layout's placement-detection
    for the `surveys.index` route (see layouts/frontend.blade.php lines 53-73).
    A hero slide for the `surveys` placement can be managed in the dashboard.
    If no slides exist yet, the hero component shows its built-in fallback
    gradient slide with brand_tagline / brand_description. No static hero
    banner is hardcoded here — the redundant plain-text header block has been
    removed entirely (its messaging lives in the hero).

    Color note: #AC8322 IS the site-wide `primary` Tailwind token
    (tailwind.config.js → `primary: '#AC8322'`). All `text-primary` /
    `bg-primary` references below already use this exact value — no
    page-scoped overrides or hardcoded hex values needed.
--}}
<x-frontend-layout title="{{ __('frontend.surveys') }}">

    @if ($surveys->count() > 0)

        {{-- ── Section heading ──────────────────────────────────────────── --}}
        <div class="mb-10 flex flex-col items-start gap-1"
             x-data="{ inView: false }"
             x-intersect.once="inView = true">
            <div class="flex items-center gap-3"
                 :class="inView ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-4'"
                 class="transition-all duration-500 ease-out">
                {{-- Decorative vertical accent bar --}}
                <span class="w-1 h-8 rounded-full bg-primary shrink-0" aria-hidden="true"></span>
                <h2 class="text-2xl sm:text-3xl font-extrabold text-text-primary tracking-tight">
                    {{ app()->getLocale() === 'ar' ? 'الاستبيانات المتاحة' : 'Available Surveys' }}
                </h2>
            </div>
            <p class="text-sm text-text-primary/60 mt-1 ms-4 font-sans"
               :class="inView ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-4'"
               class="transition-all duration-500 ease-out delay-100">
                {{ __('frontend.surveys_page_desc') }}
            </p>
        </div>

        {{-- ── Card Grid ─────────────────────────────────────────────────── --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-8"
             x-data="{ inView: false }"
             x-intersect.once="inView = true">

            @foreach ($surveys as $index => $survey)
                @php
                    $locale        = app()->getLocale();
                    $title         = $survey->title;
                    $desc          = $survey->description;
                    $img           = \App\Helpers\MediaHelper::url($survey, 'survey_images', 'image', 'card');
                    $svgPlaceholder = "data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='400' height='250' viewBox='0 0 400 250'><rect width='100%' height='100%' fill='%23EAEAE9'/><circle cx='200' cy='100' r='35' fill='%23B49C6E' opacity='.5'/><rect x='120' y='160' width='160' height='10' rx='5' fill='%23A38B54' opacity='.3'/><rect x='150' y='185' width='100' height='8' rx='4' fill='%23A38B54' opacity='.2'/></svg>";
                    $displayImg    = $img ?: $svgPlaceholder;
                    $questionCount = is_array($survey->questions) ? count($survey->questions) : 0;

                    // ── Status calculation ────────────────────────────────
                    $now        = now();
                    $statusText = '';
                    $statusClass = '';
                    $statusBadgeBg = '';
                    $isClosed   = false;
                    $isActive   = false;
                    $isEndingSoon = false;

                    if (!$survey->is_active) {
                        $statusText      = $locale === 'ar' ? 'مغلق' : 'Closed';
                        $statusBadgeBg   = 'bg-gray-400/90 text-white';
                        $isClosed        = true;
                    } elseif ($survey->ends_at && $survey->ends_at->isPast()) {
                        $statusText      = $locale === 'ar' ? 'انتهى' : 'Closed';
                        $statusBadgeBg   = 'bg-gray-400/90 text-white';
                        $isClosed        = true;
                    } elseif ($survey->ends_at && $survey->ends_at->diffInDays($now) <= 3) {
                        $statusText      = $locale === 'ar' ? 'ينتهي قريباً' : 'Ending Soon';
                        $statusBadgeBg   = 'bg-amber-500 text-white';
                        $isEndingSoon    = true;
                    } else {
                        $statusText      = $locale === 'ar' ? 'نشط الآن' : 'Active Now';
                        $statusBadgeBg   = 'bg-primary text-white';
                        $isActive        = true;
                    }

                    // ── Status-based left accent border (single source) ───
                    if ($isClosed) {
                        $borderColor = 'border-s-gray-300 dark:border-s-gray-600';
                    } elseif ($isEndingSoon) {
                        $borderColor = 'border-s-amber-400';
                    } else {
                        $borderColor = 'border-s-primary';
                    }
                @endphp

                {{-- ── Survey Card ──────────────────────────────────────── --}}
                <article
                    class="rounded-3xl border border-secondary/15 bg-background dark:bg-gray-800 shadow-sm
                           hover:shadow-lg hover:-translate-y-1.5 active:scale-[0.99]
                           transition-all duration-300 ease-out
                           select-none motion-reduce:transition-none
                           w-full flex flex-col overflow-hidden
                           border-s-4 {{ $borderColor }}
                           group"
                    :class="inView ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-8'"
                    style="transition-delay: {{ $index * 110 }}ms"
                    aria-label="{{ $title }}"
                >

                    {{-- Image ──────────────────────────────────────────── --}}
                    <div class="overflow-hidden relative aspect-[16/10] shrink-0">
                        <img src="{{ $displayImg }}"
                             alt="{{ $title }}"
                             loading="lazy"
                             class="w-full h-full object-cover
                                    transition-transform duration-500 ease-out
                                    group-hover:scale-[1.04]">
                        {{-- Hover overlay gradient --}}
                        <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent
                                    opacity-0 group-hover:opacity-100
                                    transition-opacity duration-300 pointer-events-none"
                             aria-hidden="true"></div>

                        {{-- Floating status badge on image (top-start) --}}
                        <div class="absolute top-3 start-3 flex items-center gap-1.5">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full
                                         text-[10px] font-bold tracking-wide uppercase shadow-md
                                         backdrop-blur-sm {{ $statusBadgeBg }}
                                         group-hover:scale-105 transition-transform duration-200">

                                {{-- Animated pulse dot for active surveys --}}
                                @if ($isActive)
                                    <span class="relative flex h-2 w-2" aria-hidden="true">
                                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-white opacity-75"></span>
                                        <span class="relative inline-flex rounded-full h-2 w-2 bg-white"></span>
                                    </span>
                                @endif

                                {{ $statusText }}
                            </span>
                        </div>
                    </div>

                    {{-- Body ────────────────────────────────────────────── --}}
                    <div class="flex flex-col flex-1 p-5 space-y-3">

                        {{-- Meta row: survey type + question count --}}
                        <div class="flex flex-wrap items-center justify-between gap-2">
                            {{-- Survey type pill --}}
                            <span class="px-3 py-1 rounded-full text-[11px] font-semibold
                                         bg-secondary/50 text-primary dark:bg-gray-700 dark:text-secondary
                                         tracking-wide truncate max-w-[55%]">
                                {{ $survey->type ?: __('frontend.survey_impact_assessment') }}
                            </span>

                            {{-- Question count pill with stacked-bars icon --}}
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full
                                         text-[11px] font-bold
                                         bg-primary/10 text-primary dark:bg-primary/20 dark:text-secondary">
                                {{-- Stacked-bars / chart icon — visually distinct from news cards --}}
                                <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor"
                                     viewBox="0 0 24 24" stroke-width="2.5" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z"/>
                                </svg>
                                {{ $questionCount }}
                                <span class="font-normal opacity-80">
                                    {{ $locale === 'ar' ? 'أسئلة' : 'Qs' }}
                                </span>
                            </span>
                        </div>

                        {{-- Title --}}
                        <h3 class="text-base font-bold text-text-primary dark:text-background
                                   leading-snug line-clamp-2
                                   group-hover:text-primary transition-colors duration-200">
                            {{ $title }}
                        </h3>

                        {{-- Description --}}
                        @if ($desc)
                            <p class="text-sm text-text-primary/65 dark:text-gray-300
                                      leading-relaxed line-clamp-2 font-sans flex-1">
                                {{ $desc }}
                            </p>
                        @else
                            <div class="flex-1"></div>
                        @endif

                        {{-- CTA ────────────────────────────────────────── --}}
                        <div class="pt-2">
                            @if ($isClosed)
                                <button disabled
                                        class="w-full py-2.5 rounded-2xl
                                               bg-gray-100 dark:bg-gray-700
                                               text-gray-400 dark:text-gray-500
                                               font-bold text-sm cursor-not-allowed text-center
                                               border border-gray-200 dark:border-gray-600">
                                    {{ $locale === 'ar' ? 'الاستبيان مغلق' : 'Survey Closed' }}
                                </button>
                            @else
                                <x-frontend.button
                                    :href="route('surveys.show', $survey)"
                                    variant="primary"
                                    class="w-full justify-center group/btn active:scale-[0.98]
                                           py-2.5 rounded-2xl font-bold text-sm">
                                    <span>{{ $locale === 'ar' ? 'شارك برأيك' : 'Share Your Opinion' }}</span>
                                    <svg class="w-4 h-4 inline-block ms-1.5 rtl:rotate-180
                                                transform transition-transform duration-300
                                                group-hover/btn:translate-x-1 rtl:group-hover/btn:-translate-x-1"
                                         fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                         aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                                    </svg>
                                </x-frontend.button>
                            @endif
                        </div>

                    </div>{{-- /body --}}
                </article>

            @endforeach
        </div>{{-- /grid --}}

        {{-- Pagination ────────────────────────────────────────────────── --}}
        <x-frontend.pagination :paginator="$surveys" />

    @else

        {{-- Empty state ──────────────────────────────────────────────── --}}
        <x-frontend.empty-state
            :title="__('frontend.no_surveys_available')"
            :description="__('frontend.surveys_coming_soon')"
        />

    @endif

</x-frontend-layout>
