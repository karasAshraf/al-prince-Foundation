@props(['sections'])

@php
    $locale = app()->getLocale();
    $isRtl  = $locale === 'ar';
@endphp

{{-- ============================================================
     Statistics / Counter Section — Full Width Corporate Premium
     ============================================================ --}}
<section
    {{ $attributes->merge(['class' => 'relative w-full py-16 md:py-20 overflow-hidden bg-[#F5F5F5] dark:bg-gray-900 border-b border-border/10']) }}
    aria-label="{{ __('frontend.statistics_section') ?: 'Statistics' }}"
    x-data="{ sectionInView: false }"
    x-intersect.once="sectionInView = true"
>
    {{-- ── Background Glow & Floating Decorations ───────────── --}}
    <div class="absolute inset-0 pointer-events-none -z-10" aria-hidden="true">
        {{-- Radial Top-Start Glow --}}
        <div class="absolute -top-40 -start-40 w-[500px] h-[500px] bg-gradient-to-br from-primary-light/10 to-transparent rounded-full blur-3xl opacity-70 dark:opacity-20"></div>

        {{-- Radial Bottom-End Glow --}}
        <div class="absolute -bottom-40 -end-40 w-[500px] h-[500px] bg-gradient-to-tl from-primary-light/10 to-transparent rounded-full blur-3xl opacity-60 dark:opacity-20"></div>
    </div>

    {{-- ── Container (Max Width 1280px) ───────────────────────── --}}
    <x-frontend.container class="relative z-10">

        {{-- Responsive Grid: Desktop (4 cols), Laptop (4 cols), Tablet (2 cols), Mobile (1 col) --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8 lg:gap-10 items-stretch">

            @forelse ($sections as $index => $counter)
                @php
                    $label   = $isRtl
                        ? ($counter->title_ar ?? '')
                        : ($counter->title_en ?? $counter->title_ar ?? '');

                    $number  = (string) ($counter->counter_number ?? $counter->counterNumber ?? '0');
                    $iconVal = (string) ($counter->counter_icon   ?? $counter->counterIcon   ?? 'sparkles');
                    $delayMs = $index * 100; // 100ms staggered entrance delay
                @endphp

                {{-- ── Card Component ───────────────────────────── --}}
                <article
                    x-data="{
                        rawInput:    @js($number),
                        current:     0,
                        target:      0,
                        prefix:      '',
                        suffix:      '',
                        duration:    1800,
                        started:     false,
                        completed:   false,
                        cardVisible: false,

                        init() {
                            this.parseNumber();

                            // Trigger entrance animation when section enters viewport
                            this.$watch('$root.sectionInView', (val) => {
                                if (val) {
                                    setTimeout(() => { this.cardVisible = true; }, {{ $delayMs + 50 }});
                                }
                            });

                            // Robust Fallback: display card after timeout
                            setTimeout(() => { this.cardVisible = true; }, {{ $delayMs + 300 }});
                        },

                        parseNumber() {
                            const raw = String(this.rawInput || '0').trim();
                            // Convert Eastern-Arabic numerals (٠١٢٣٤٥٦٧٨٩ → 0-9)
                            const ascii = raw.replace(/[٠-٩]/g, d => '٠١٢٣٤٥٦٧٨٩'.indexOf(d).toString());

                            // Extrapolate prefix/suffix
                            const matches = ascii.match(/^([^\d]*?)(\d[\d,.]*)([^\d]*)$/);
                            if (matches) {
                                this.prefix = matches[1] || '';
                                this.suffix = matches[3] || '';
                                this.target = parseFloat(matches[2].replace(/,/g, '')) || 0;
                            } else {
                                this.target = parseFloat(ascii) || 0;
                            }
                        },

                        startCounter() {
                            if (this.started) return;
                            this.started = true;
                            let start = null;

                            const step = (timestamp) => {
                                if (!start) start = timestamp;
                                const progress = Math.min((timestamp - start) / this.duration, 1);
                                const eased   = 1 - Math.pow(1 - progress, 3);
                                this.current  = Math.round(eased * this.target);
                                if (progress < 1) {
                                    requestAnimationFrame(step);
                                } else {
                                    this.current   = this.target;
                                    this.completed = true;
                                }
                            };
                            requestAnimationFrame(step);
                        },

                        get displayValue() {
                            if (!this.started) return this.rawInput;
                            return this.prefix + this.current.toLocaleString() + this.suffix;
                        }
                    }"
                    x-intersect.once="startCounter()"
                    :class="cardVisible ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-10'"
                    class="stat-card
                           group relative flex flex-col items-center text-center
                           bg-white/80 dark:bg-gray-900/80
                           backdrop-blur-md
                           border border-border dark:border-gray-800
                           rounded-3xl
                           px-8 py-10
                           h-full min-h-[300px]
                           shadow-sm
                           hover:shadow-lg
                           hover:border-primary dark:hover:border-primary-light
                           hover:-translate-y-2.5 hover:scale-[1.03]
                           cursor-pointer
                           transition-all duration-300 ease-out
                           focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary/60"
                    tabindex="0"
                    role="region"
                    :aria-label="@js($label)"
                >
                    {{-- Soft Card Hover Background Glow --}}
                    <div class="absolute inset-0 rounded-3xl bg-gradient-to-br from-primary-light/10 via-transparent to-primary/10 opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none" aria-hidden="true"></div>

                    {{-- Top Border Highlight Accent --}}
                    <div class="absolute inset-x-0 top-0 h-1 rounded-t-3xl bg-gradient-to-r from-primary via-primary-light to-primary opacity-0 group-hover:opacity-100 transition-opacity duration-300" aria-hidden="true"></div>

                    {{-- ── Icon Container ────────────────────────────── --}}
                    <div class="relative mb-8 shrink-0">
                        {{-- Soft Glow Behind Icon --}}
                        <div class="absolute inset-0 rounded-full bg-gradient-to-br from-primary-light to-primary opacity-30 blur-xl scale-100 group-hover:scale-125 group-hover:opacity-60 transition-all duration-300" aria-hidden="true"></div>

                        {{-- Icon Circle (80px) --}}
                        <div class="relative z-10 flex h-20 w-20 items-center justify-center rounded-full bg-gradient-to-br from-primary-light to-primary shadow-lg shadow-primary/25 group-hover:scale-115 group-hover:rotate-8 transition-transform duration-300 ease-out">
                            <x-icon
                                :name="$iconVal"
                                class="w-10 h-10 text-white"
                                fallback="sparkles"
                            />
                        </div>
                    </div>

                    {{-- ── Number & Label Content ────────────────────── --}}
                    <div class="flex-1 flex flex-col items-center justify-center space-y-4 w-full">
                        {{-- Visual Focus: Counter Number --}}
                        <span
                            class="block font-black tracking-tight leading-none text-5xl lg:text-[56px] text-text-primary dark:text-white group-hover:text-primary dark:group-hover:text-primary-light group-hover:scale-105 transition-all duration-300 origin-center"
                            x-text="displayValue"
                            aria-live="polite"
                            aria-atomic="true"
                        >{{ $number }}</span>

                        {{-- Subtle Dot Divider --}}
                        <div class="flex items-center gap-2 w-full justify-center opacity-60" aria-hidden="true">
                            <span class="h-px w-8 bg-gradient-to-r from-transparent to-primary-light"></span>
                            <span class="h-1.5 w-1.5 rounded-full bg-primary"></span>
                            <span class="h-px w-8 bg-gradient-to-l from-transparent to-primary-light"></span>
                        </div>

                        {{-- Label Underneath --}}
                        <span class="block text-[20px] font-semibold text-text-secondary dark:text-gray-300 leading-snug group-hover:text-text-primary dark:group-hover:text-white transition-colors duration-300">
                            {{ $label }}
                        </span>
                    </div>
                </article>

            @empty
                <div class="col-span-full py-16 text-center text-text-secondary/50 dark:text-gray-400 text-base font-medium">
                    {{ __('frontend.no_statistics') ?: 'No statistics to display.' }}
                </div>
            @endforelse

        </div>{{-- /grid --}}

    </x-frontend.container>{{-- /container --}}
</section>

@once
<style>
    .stat-card {
        transition-property: opacity, transform, box-shadow, border-color;
        transition-duration: 700ms;
        transition-timing-function: cubic-bezier(0.16, 1, 0.3, 1);
    }

    .stat-card:hover {
        transition-duration: 300ms;
    }

    @media (prefers-reduced-motion: reduce) {
        .stat-card {
            transition: none !important;
            transform: none !important;
            opacity: 1 !important;
        }
    }
</style>
@endonce
