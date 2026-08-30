<x-frontend-layout title="{{ __('frontend.surveys') }}">

    <!-- Page Header -->
    <div class="text-center mb-12">
        <x-frontend.badge variant="secondary">{{ __('frontend.available_surveys') }}</x-frontend.badge>
        <h1 class="text-3xl sm:text-4xl font-bold text-text-primary dark:text-surface mt-3 leading-tight">
            {{ __('frontend.surveys') }}
        </h1>
        <p class="mt-4 text-text-primary/70 dark:text-gray-400 max-w-xl mx-auto">
            {{ __('frontend.surveys_page_desc') }}
        </p>
    </div>

    @if ($surveys->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-8"
             x-data="{ inView: false }"
             x-intersect.once="inView = true">
            @foreach ($surveys as $index => $survey)
                @php
                    $locale = app()->getLocale();
                    $title  = $survey->title;
                    $desc   = $survey->description;
                    $img    = \App\Helpers\MediaHelper::url($survey, 'survey_images', 'image', 'card');
                    $svgPlaceholder = "data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='400' height='250' viewBox='0 0 400 250'><rect width='100%' height='100%' fill='%23EAEAE9'/><circle cx='200' cy='100' r='35' fill='%23B49C6E' opacity='.5'/><rect x='120' y='160' width='160' height='10' rx='5' fill='%23A38B54' opacity='.3'/><rect x='150' y='185' width='100' height='8' rx='4' fill='%23A38B54' opacity='.2'/></svg>";
                    $displayImg = $img ?: $svgPlaceholder;
                    $questionCount = is_array($survey->questions) ? count($survey->questions) : 0;
                    
                    // Rotating left accent border colors based on index
                    $borderColors = ['border-primary', 'border-primary-light', 'border-secondary-light', 'border-[#A38B54]/60'];
                    $borderColor = $borderColors[$index % count($borderColors)];

                    // Status calculations
                    $now = now();
                    $statusText = '';
                    $statusClass = '';
                    $isClosed = false;

                    if (!$survey->is_active) {
                        $statusText = $locale === 'ar' ? 'مغلق' : 'Closed';
                        $statusClass = 'bg-gray-400 text-white';
                        $isClosed = true;
                    } elseif ($survey->ends_at && $survey->ends_at->isPast()) {
                        $statusText = $locale === 'ar' ? 'انتهى' : 'Closed';
                        $statusClass = 'bg-gray-400 text-white';
                        $isClosed = true;
                    } elseif ($survey->ends_at && $survey->ends_at->diffInDays($now) <= 3) {
                        $statusText = $locale === 'ar' ? 'ينتهي قريباً' : 'Ending Soon';
                        $statusClass = 'bg-amber-500 text-white';
                    } else {
                        $statusText = $locale === 'ar' ? 'نشط الآن' : 'Active Now';
                        $statusClass = 'bg-primary text-white';
                    }
                @endphp

                <!-- Distinct Poll/Survey Card -->
                <div class="rounded-3xl border border-primary-light/15 bg-white dark:bg-gray-800 shadow-sm hover:shadow-md transition-all duration-300 ease-out hover:-translate-y-1 active:scale-[0.99] select-none motion-reduce:transition-none w-full flex flex-col justify-between overflow-hidden border-s-4 {{ $borderColor }} group"
                     :class="inView ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-8'"
                     style="transition-delay: {{ $index * 120 }}ms">
                    
                    <div>
                        {{-- Aspect Ratio Image --}}
                        <div class="overflow-hidden relative aspect-[16/10]">
                            <img src="{{ $displayImg }}" alt="{{ $title }}" loading="lazy"
                                 class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-103">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/15 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none"></div>
                        </div>

                        <div class="p-6 space-y-4">
                            {{-- Header meta row --}}
                            <div class="flex flex-wrap items-center justify-between gap-2">
                                <span class="px-3 py-1 rounded-full text-xs font-semibold bg-secondary-light/40 text-primary">
                                    {{ $survey->type ?: __('frontend.survey_impact_assessment') }}
                                </span>
                                
                                <div class="flex items-center gap-2">
                                    {{-- Status Badge --}}
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold tracking-wide uppercase shadow-sm {{ $statusClass }}">
                                        {{ $statusText }}
                                    </span>
                                    
                                    {{-- Questions pill --}}
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-primary/10 text-primary">
                                        <x-icon name="clipboard-list" class="w-3.5 h-3.5" />
                                        {{ $questionCount }} {{ $locale === 'ar' ? 'أسئلة' : 'Questions' }}
                                    </span>
                                </div>
                            </div>

                            {{-- Title --}}
                            <h3 class="text-lg font-bold text-text-primary dark:text-gray-100 leading-snug line-clamp-2">
                                {{ $title }}
                            </h3>

                            {{-- Description --}}
                            @if ($desc)
                                <p class="text-sm text-text-primary/70 dark:text-gray-300 leading-relaxed line-clamp-2 font-sans">
                                    {{ $desc }}
                                </p>
                            @endif
                        </div>
                    </div>

                    {{-- CTA Section --}}
                    <div class="px-6 pb-6 pt-2">
                        @if ($isClosed)
                            <button disabled class="w-full py-3 rounded-2xl bg-gray-100 dark:bg-gray-700 text-gray-400 font-bold text-sm cursor-not-allowed text-center">
                                {{ $locale === 'ar' ? 'الاستبيان مغلق' : 'Survey Closed' }}
                            </button>
                        @else
                            <x-frontend.button :href="route('surveys.show', $survey)" variant="primary" class="w-full justify-center group/btn active:scale-[0.98]">
                                <span>{{ $locale === 'ar' ? 'شارك برأيك' : 'Share Your Opinion' }}</span>
                                <svg class="w-4 h-4 inline-block ms-1.5 transform transition-transform duration-300 group-hover/btn:translate-x-1 rtl:group-hover/btn:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                                </svg>
                            </x-frontend.button>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <x-frontend.pagination :paginator="$surveys" />
    @else
        <x-frontend.empty-state
            :title="__('frontend.no_surveys_available')"
            :description="__('frontend.surveys_coming_soon')"
        />
    @endif

</x-frontend-layout>
