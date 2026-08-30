<x-frontend-layout title="{{ __('frontend.development_programs') }}">

    <!-- Page Header 
    <div class="text-center mb-12">
        <x-frontend.badge variant="secondary">{{ __('frontend.our_programs') }}</x-frontend.badge>
        <h1 class="text-3xl sm:text-4xl font-bold text-text-primary dark:text-surface mt-3 leading-tight">
            {{ __('frontend.programs_and_initiatives') }}
        </h1>
        <p class="mt-4 text-text-primary/70 dark:text-gray-400 max-w-xl mx-auto">
            {{ __('frontend.programs_page_desc') }}
        </p>
    </div>-->

    @if ($programs->count() > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-8"
             x-data="{ inView: false }"
             x-intersect.once="inView = true">
            @foreach ($programs as $index => $program)
                @php
                    $locale    = app()->getLocale();
                    $title     = $locale === 'ar' ? $program->title_ar : ($program->title_en ?? $program->title_ar);
                    $desc      = $locale === 'ar' ? $program->description_ar : ($program->description_en ?? $program->description_ar);
                    $img       = \App\Helpers\MediaHelper::url($program, 'program_images', 'image', 'card');
                    $detailUrl = route('programs.show', $program->slug);
                @endphp

                <!-- Editorial Overlay Card -->
                <div class="relative overflow-hidden rounded-2xl aspect-[4/5] shadow-md transition-all duration-300 group hover:shadow-xl hover:-translate-y-1 active:scale-[0.99] focus-within:ring-2 focus-within:ring-secondary-light outline-none select-none text-start flex flex-col justify-end"
                     :class="inView ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-6'"
                     style="transition-delay: {{ $index * 100 }}ms; transition-duration: 700ms;">
                    
                    {{-- Full-bleed image --}}
                    @if ($img)
                        <img src="{{ $img }}" alt="{{ $title }}" loading="lazy"
                             class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 ease-out group-hover:scale-105 pointer-events-none z-0">
                    @else
                        <div class="absolute inset-0 bg-gradient-to-br from-[#A38B54] to-[#B49C6E]/60 pointer-events-none z-0"></div>
                    @endif

                    {{-- Permanent gradient overlay --}}
                    <div class="absolute inset-0 bg-gradient-to-t from-[#3D342A]/95 via-[#3D342A]/40 to-transparent pointer-events-none z-0"></div>

                    {{-- Status Ribbon --}}
                    <div class="absolute top-4 -end-2 rotate-12 rtl:-rotate-12 px-4 py-1 text-[10px] font-bold uppercase tracking-wider shadow-md text-white z-10 bg-primary">
                        {{ $program->status === 'active' ? ($locale === 'ar' ? 'نشط' : 'Active') : ($locale === 'ar' ? 'غير نشط' : 'Inactive') }}
                    </div>

                    {{-- Bottom Content Overlay --}}
                    <div class="absolute bottom-0 start-0 end-0 p-5 sm:p-6 z-10 space-y-2 select-text">
                        <span class="text-xs uppercase tracking-wide font-semibold text-secondary-light">
                            {{ $locale === 'ar' ? 'برنامج تنموي' : 'Development Program' }}
                        </span>

                        <h3 class="text-white text-lg font-bold leading-snug line-clamp-2">
                            <a href="{{ $detailUrl }}" class="hover:text-secondary-light transition-colors focus:outline-none focus-visible:underline">
                                {{ $title }}
                            </a>
                        </h3>

                        {{-- Hover-Reveal block --}}
                        <div class="transition-all duration-500 ease-out overflow-hidden max-h-32 opacity-100 md:max-h-0 md:opacity-0 md:group-hover:max-h-32 md:group-hover:opacity-100 md:group-focus-within:max-h-32 md:group-focus-within:opacity-100 space-y-3">
                            @if ($desc)
                                <p class="text-white/85 text-sm leading-relaxed line-clamp-2 font-sans">
                                    {{ $desc }}
                                </p>
                            @endif

                            <div class="pt-1">
                                <a href="{{ $detailUrl }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-secondary-light hover:text-white transition-colors group/btn">
                                    <span>{{ $locale === 'ar' ? 'التفاصيل' : 'Details' }}</span>
                                    <svg class="w-3.5 h-3.5 transform transition-transform duration-300 group-hover/btn:translate-x-1 rtl:group-hover/btn:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <x-frontend.pagination :paginator="$programs" />
    @else
        <x-frontend.empty-state
            :title="__('frontend.no_programs_available')"
            :description="__('frontend.programs_coming_soon')"
        />
    @endif

</x-frontend-layout>
