<x-frontend-layout title="{{ __('frontend.events') }}">

    {{-- ═══════════════════════════════════════════════════════════════
         TWO-TONE SECTION HEADING
    ═══════════════════════════════════════════════════════════════ --}}
    <div class="text-center mb-12 space-y-3">
        <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-secondary/60 text-primary text-xs font-semibold tracking-widest uppercase">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            {{ __('frontend.our_events') }}
        </span>

        <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold leading-tight">
            @if(app()->getLocale() === 'ar')
                <span class="text-text-primary">الفعاليات</span>
                <span class="bg-gradient-to-r from-primary to-primary-light bg-clip-text text-transparent"> والورش القادمة</span>
            @else
                <span class="text-text-primary">Upcoming</span>
                <span class="bg-gradient-to-r from-primary to-primary-light bg-clip-text text-transparent"> Events & Workshops</span>
            @endif
        </h1>

        <p class="mt-2 text-text-primary/65 max-w-lg mx-auto text-sm sm:text-base leading-relaxed">
            {{ __('frontend.events_page_desc') }}
        </p>

        {{-- Decorative underline bar --}}
        <div class="flex items-center justify-center gap-2 pt-1">
            <span class="h-px w-12 bg-secondary/40 rounded-full"></span>
            <span class="w-2 h-2 rounded-full bg-primary"></span>
            <span class="h-px w-24 bg-primary/60 rounded-full"></span>
            <span class="w-2 h-2 rounded-full bg-primary"></span>
            <span class="h-px w-12 bg-secondary/40 rounded-full"></span>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════════════════
         TICKET-STYLE TIMELINE LIST
    ═══════════════════════════════════════════════════════════════ --}}
    @if ($events->count() > 0)

        <div class="space-y-5 max-w-4xl mx-auto"
             x-data="{ inView: false }"
             x-intersect.once="inView = true">

            @foreach ($events as $index => $item)
                @php
                    $locale    = app()->getLocale();
                    $title     = $locale === 'ar' ? ($item->title_ar ?: '') : ($item->title_en ?: $item->title_ar);
                    $desc      = $locale === 'ar' ? ($item->description_ar ?: '') : ($item->description_en ?: $item->description_ar);
                    $detailUrl = route('events.show', $item->slug);

                    // Use created_at as the display date proxy (no event_date column exists)
                    $eventDate  = $item->created_at;
                    $dayNum     = $eventDate->format('d');
                    $monthNames = [
                        'ar' => ['يناير','فبراير','مارس','أبريل','مايو','يونيو',
                                 'يوليو','أغسطس','سبتمبر','أكتوبر','نوفمبر','ديسمبر'],
                        'en' => ['JAN','FEB','MAR','APR','MAY','JUN',
                                 'JUL','AUG','SEP','OCT','NOV','DEC'],
                    ];
                    $monthName  = $monthNames[$locale][(int)$eventDate->format('n') - 1];

                    // "New" badge: created within the last 7 days
                    $isNew    = $eventDate->diffInDays(now()) <= 7;
                    $newLabel = $locale === 'ar' ? 'جديد' : 'New';

                    // Category label (no category field — use a generic branded label)
                    $categoryLabel = $locale === 'ar' ? 'فعالية' : 'Event';
                @endphp

                {{-- Ticket Card --}}
                <div class="group rounded-2xl overflow-hidden bg-background shadow-sm hover:shadow-md
                            transition-all duration-300 border border-secondary/10
                            flex flex-col sm:flex-row"
                     :class="inView ? 'opacity-100 translate-x-0' : 'opacity-0 {{ app()->getLocale() === 'ar' ? '-translate-x-8' : 'translate-x-8' }}'"
                     style="transition: opacity 700ms ease, transform 700ms ease; transition-delay: {{ $index * 120 }}ms;">

                    {{-- ── DATE STUB (LEFT on desktop, TOP on mobile) ── --}}
                    <div class="relative sm:w-28 shrink-0 bg-primary text-background
                                flex sm:flex-col flex-row
                                items-center justify-center
                                sm:py-6 sm:px-4
                                py-3 px-6 gap-3 sm:gap-1
                                {{ $isNew ? 'ring-2 ring-primary-light/50' : '' }}">

                        {{-- Pulse ring when "new" --}}
                        @if($isNew)
                            <span class="absolute inset-0 rounded-none sm:rounded-s-2xl animate-pulse bg-secondary/10 pointer-events-none"></span>
                        @endif

                        <span class="text-4xl sm:text-5xl font-extrabold leading-none tabular-nums">
                            {{ $dayNum }}
                        </span>
                        <span class="text-xs font-semibold uppercase tracking-widest opacity-90">
                            {{ $monthName }}
                        </span>

                        {{-- Dashed perforation line — desktop only --}}
                        <div class="hidden sm:block absolute top-0 end-0 bottom-0 w-px">
                            {{-- Top cutout circle --}}
                            <span class="absolute -top-3 -end-2.5 w-5 h-5 rounded-full bg-background border border-secondary/10 shadow-inner z-10"></span>
                            {{-- Dashed line --}}
                            <span class="absolute inset-y-3 end-0 w-px border-dashed border-e-2 border-secondary/30"></span>
                            {{-- Bottom cutout circle --}}
                            <span class="absolute -bottom-3 -end-2.5 w-5 h-5 rounded-full bg-background border border-secondary/10 shadow-inner z-10"></span>
                        </div>
                    </div>

                    {{-- ── CONTENT AREA (RIGHT on desktop, BOTTOM on mobile) ── --}}
                    <div class="flex-1 p-5 sm:p-6 flex flex-col justify-between gap-3 sm:ps-8">

                        {{-- Top row: category pill + optional "New" badge --}}
                        <div class="flex items-center gap-2 flex-wrap">
                            <span class="inline-flex items-center px-3 py-0.5 rounded-full text-xs font-semibold
                                         bg-secondary/50 text-primary border border-secondary">
                                {{ $categoryLabel }}
                            </span>

                            @if($isNew)
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-bold
                                             bg-secondary/20 text-primary border border-secondary/40">
                                    <span class="w-1.5 h-1.5 rounded-full bg-primary animate-pulse"></span>
                                    {{ $newLabel }}
                                </span>
                            @endif
                        </div>

                        {{-- Title --}}
                        <div>
                            <h2 class="text-base sm:text-lg font-bold text-text-primary leading-snug line-clamp-2
                                        group-hover:text-primary transition-colors duration-200">
                                <a href="{{ $detailUrl }}"
                                   class="focus:outline-none focus-visible:ring-2 focus-visible:ring-primary rounded">
                                    {{ $title }}
                                </a>
                            </h2>

                            @if($desc)
                                <p class="mt-1.5 text-sm text-text-primary/65 leading-relaxed line-clamp-2">
                                    {{ $desc }}
                                </p>
                            @endif
                        </div>

                        {{-- CTA row --}}
                        <div class="flex items-center justify-between pt-2 border-t border-secondary/10">
                            <a href="{{ $detailUrl }}"
                               class="inline-flex items-center gap-1.5 text-sm font-semibold text-primary
                                      hover:text-primary/80 transition-colors duration-200
                                      focus:outline-none focus-visible:ring-2 focus-visible:ring-primary rounded">
                                {{ __('frontend.event_details') }}
                                {{-- Arrow flips for RTL via transform --}}
                                <svg class="w-4 h-4 transition-transform duration-200
                                            group-hover:translate-x-1 rtl:rotate-180 rtl:group-hover:-translate-x-1"
                                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                          d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                                </svg>
                            </a>

                            {{-- Year hint --}}
                            <span class="text-xs text-text-primary/40 tabular-nums" dir="ltr">
                                {{ $eventDate->format('Y') }}
                            </span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-10 max-w-4xl mx-auto">
            <x-frontend.pagination :paginator="$events" />
        </div>

    @else
        <x-frontend.empty-state
            :title="__('frontend.no_events_available')"
            :description="__('frontend.events_coming_soon')"
        />
    @endif

</x-frontend-layout>
