<x-frontend-layout title="{{ __('frontend.about_foundation') }}">

    @php
        $locale = app()->getLocale();
        
        // 1. Identify Key Sections
        $overviewSection = $aboutSections->first(fn($s) => $s->slug === 'nbth');
        $establishmentSection = $aboutSections->first(fn($s) => $s->slug === 'altasys');
        
        // 2. Identify Pillars (Vision, Mission, Goals, Values)
        $visionSection = $aboutSections->first(fn($s) => $s->slug === 'roytna');
        $missionSection = $aboutSections->first(fn($s) => $s->slug === 'rsaltna');
        $goalsSection = $aboutSections->first(fn($s) => $s->slug === 'ahdafna');
        $valuesSection = $aboutSections->first(fn($s) => $s->slug === 'a');
        
        // 3. Collect any other sections
        $excludeSlugs = ['nbth', 'altasys', 'roytna', 'rsaltna', 'ahdafna', 'a', 'test-about'];
        $otherSections = $aboutSections->reject(fn($s) => in_array($s->slug, $excludeSlugs));
    @endphp

    <!-- 1. Overview Section -->
    @if ($overviewSection)
        @php
            $oTitle = $locale === 'ar' ? $overviewSection->title_ar : ($overviewSection->title_en ?? $overviewSection->title_ar);
            $oDesc  = $locale === 'ar' ? $overviewSection->description_ar : ($overviewSection->description_en ?? $overviewSection->description_ar);
            $oImg   = \App\Helpers\MediaHelper::url($overviewSection, 'about_images', 'image', 'detail');
        @endphp
        <section class="py-12 md:py-16 overflow-hidden"
                 x-data="{ inView: false }"
                 x-intersect.once="inView = true">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-12 items-center">
                
                {{-- Text Content (Animate first, delay-0) --}}
                <div class="space-y-4 transition-all duration-700 ease-out transform motion-reduce:transition-none motion-reduce:transform-none"
                     :class="inView ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-6'">
                    <span class="text-sm font-semibold text-brand-primary tracking-wider uppercase block">
                        {{ $locale === 'ar' ? 'عن المؤسسة' : 'About Foundation' }}
                    </span>
                    <h2 class="text-2xl sm:text-3xl font-bold text-brand-text leading-snug">
                        {{ $oTitle }}
                    </h2>
                    <div class="text-base leading-relaxed text-brand-text/70 max-w-prose space-y-4">
                        {!! nl2br(e($oDesc)) !!}
                    </div>
                    <div class="pt-2">
                        <x-frontend.external-link-button :model="$overviewSection" collection="about_images" class="!px-5 !py-2.5 !text-xs active:scale-[0.98]" />
                    </div>
                </div>

                {{-- Responsive Image Container (Animate second, delay-150) --}}
                @if ($oImg)
                    <div class="relative overflow-hidden rounded-3xl group shadow-sm hover:shadow-md transition-all duration-700 ease-out transform motion-reduce:transition-none motion-reduce:transform-none"
                         :class="inView ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-6'"
                         style="transition-delay: 150ms">
                        <div class="aspect-[4/3] w-full bg-[#FDFEF6] dark:bg-gray-800">
                            <img src="{{ $oImg }}" alt="{{ $oTitle }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-[1.02]" loading="lazy">
                        </div>
                    </div>
                @endif

            </div>
        </section>
    @endif
    <!-- 2. Establishment Section -->
    @if ($establishmentSection)
        @php
            $eTitle = $locale === 'ar' ? $establishmentSection->title_ar : ($establishmentSection->title_en ?? $establishmentSection->title_ar);
            $eDesc  = $locale === 'ar' ? $establishmentSection->description_ar : ($establishmentSection->description_en ?? $establishmentSection->description_ar);
            $eImg   = \App\Helpers\MediaHelper::url($establishmentSection, 'about_images', 'image', 'detail');
        @endphp
        <section class="py-12 md:py-16 border-t border-brand-accent/10 overflow-hidden"
                 x-data="{ inView: false }"
                 x-intersect.once="inView = true">
            <div class="rounded-3xl border border-brand-accent/15 bg-brand-secondary/5 dark:bg-gray-800/10 p-6 sm:p-8 md:p-12 shadow-sm transition-all duration-500 ease-out transform"
                 :class="inView ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-4'">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-12 items-center">
                    
                    {{-- Text Content (Animate first, delay-0) --}}
                    <div class="space-y-4 transition-all duration-700 ease-out transform motion-reduce:transition-none motion-reduce:transform-none"
                         :class="inView ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-6'">
                        <span class="text-sm font-semibold text-brand-primary tracking-wider uppercase block">
                            {{ $locale === 'ar' ? 'التأسيس' : 'Establishment' }}
                        </span>
                        <h2 class="text-2xl sm:text-3xl font-bold text-brand-text leading-snug">
                            {{ $eTitle }}
                        </h2>
                        <div class="text-base leading-relaxed text-brand-text/70 max-w-prose space-y-4">
                            {!! nl2br(e($eDesc)) !!}
                        </div>
                        <div class="pt-2">
                            <x-frontend.external-link-button :model="$establishmentSection" collection="about_images" class="!px-5 !py-2.5 !text-xs active:scale-[0.98]" />
                        </div>
                    </div>

                    {{-- Responsive Image Container (Animate second, delay-150) --}}
                    @if ($eImg)
                        <div class="relative overflow-hidden rounded-3xl group shadow-sm hover:shadow-md transition-all duration-700 ease-out transform motion-reduce:transition-none motion-reduce:transform-none"
                             :class="inView ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-6'"
                             style="transition-delay: 150ms">
                            <div class="aspect-[4/3] w-full bg-[#FDFEF6] dark:bg-gray-800">
                                <img src="{{ $eImg }}" alt="{{ $eTitle }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-[1.02]" loading="lazy">
                            </div>
                        </div>
                    @endif

                </div>
            </div>
        </section>
    @endif

    <section class="pt-12 sm:pt-16 pb-16 border-t border-brand-accent/10"
             x-data="{ inView: false }"
             x-intersect.once="inView = true">

        <div class="space-y-6 lg:space-y-8">
            
            <!-- Row 1: Vision (60%) and Mission (40%) -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 lg:gap-8 items-stretch">
                
                <!-- Vision: 60% Width (lg:col-span-3) -->
                @if ($visionSection)
                    @php
                        $vTitle = $locale === 'ar' ? $visionSection->title_ar : ($visionSection->title_en ?? $visionSection->title_ar);
                        $vDesc  = $locale === 'ar' ? $visionSection->description_ar : ($visionSection->description_en ?? $visionSection->description_ar);
                        $vImg   = \App\Helpers\MediaHelper::url($visionSection, 'about_images', 'image', 'thumb');
                    @endphp
                    <div class="col-span-1 md:col-span-1 lg:col-span-3 rounded-3xl border border-brand-accent/20 bg-white dark:bg-gray-800 p-8 shadow-sm hover:shadow-md transition-all duration-300 ease-out hover:-translate-y-1 active:scale-[0.99] active:shadow-sm focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-primary/40 focus-visible:ring-offset-2 dark:focus-visible:ring-offset-gray-900 transform cursor-default select-none motion-reduce:transition-none motion-reduce:transform-none w-full group"
                         tabindex="0"
                         :class="inView ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-6'"
                         style="transition-delay: 0ms">
                        
                        <div class="flex flex-row items-start gap-4 w-full">
                            
                            {{-- Text content (First in LTR layout) --}}
                            <div class="space-y-3 flex-1 w-full">
                                <h3 class="text-xl font-bold text-brand-text dark:text-brand-bg leading-snug">
                                    {{ $vTitle }}
                                </h3>
                                <p class="text-base text-brand-text/70 dark:text-gray-300 leading-relaxed font-sans max-w-prose">
                                    {{ $vDesc }}
                                </p>
                            </div>

                            {{-- Image Container (Second in LTR layout) --}}
                            @if ($vImg)
                                <div class="w-16 h-16 flex items-center justify-center shrink-0 p-2 rounded-lg">
                                    <img src="{{ $vImg }}" alt="{{ $vTitle }}" class="w-full h-full object-cover transition-transform duration-200 group-hover:scale-105" loading="lazy">
                                </div>
                            @endif

                        </div>
                    </div>
                @endif

                <!-- Mission: 40% Width (lg:col-span-2) -->
                @if ($missionSection)
                    @php
                        $mTitle = $locale === 'ar' ? $missionSection->title_ar : ($missionSection->title_en ?? $missionSection->title_ar);
                        $mDesc  = $locale === 'ar' ? $missionSection->description_ar : ($missionSection->description_en ?? $missionSection->description_ar);
                        $mImg   = \App\Helpers\MediaHelper::url($missionSection, 'about_images', 'image', 'thumb');
                    @endphp
                    <div class="col-span-1 md:col-span-1 lg:col-span-2 rounded-3xl border border-brand-accent/20 bg-white dark:bg-gray-800 p-8 shadow-sm hover:shadow-md transition-all duration-300 ease-out hover:-translate-y-1 active:scale-[0.99] active:shadow-sm focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-primary/40 focus-visible:ring-offset-2 dark:focus-visible:ring-offset-gray-900 transform cursor-default select-none motion-reduce:transition-none motion-reduce:transform-none w-full group"
                         tabindex="0"
                         :class="inView ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-6'"
                         style="transition-delay: 100ms">
                        
                        <div class="flex flex-row items-start gap-6 w-full">
                            
                            {{-- Text content (First in LTR layout) --}}
                            <div class="space-y-3 flex-1 w-full">
                                <h3 class="text-xl font-bold text-brand-text dark:text-brand-bg leading-snug">
                                    {{ $mTitle }}
                                </h3>
                                <p class="text-base text-brand-text/70 dark:text-gray-300 leading-relaxed font-sans max-w-prose">
                                    {{ $mDesc }}
                                </p>
                            </div>

                            {{-- Image Container (Second in LTR layout) --}}
                            @if ($mImg)
                                <div class="w-14 h-14 rounded-2xl bg-brand-secondary/40 dark:bg-brand-accent/20 flex items-center justify-center shrink-0 transition-all duration-300 group-hover:bg-brand-secondary/60 dark:group-hover:bg-brand-accent/30 shadow-sm p-2">
                                    <img src="{{ $mImg }}" alt="{{ $mTitle }}" class="w-full h-full object-contain transition-transform duration-200 group-hover:scale-105" loading="lazy">
                                </div>
                            @endif

                        </div>
                    </div>
                @endif

            </div>

            <!-- Row 2: Goals (60%) and Values (40%) -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 lg:gap-8 items-stretch">
                
                <!-- Goals: 60% Width (lg:col-span-3) -->
                @if ($goalsSection)
                    @php
                        $gTitle = $locale === 'ar' ? $goalsSection->title_ar : ($goalsSection->title_en ?? $goalsSection->title_ar);
                        $gDesc  = $locale === 'ar' ? $goalsSection->description_ar : ($goalsSection->description_en ?? $goalsSection->description_ar);
                        $gImg   = \App\Helpers\MediaHelper::url($goalsSection, 'about_images', 'image', 'thumb');
                        
                        $gGoals = [];
                        if (!empty($gDesc)) {
                            preg_match_all('/([1-9]\d*)\s*[\t\.\-]?\s*(.+?)(?=\s*[1-9]\d*\s*[\t\.\-]?\s*|$)/us', $gDesc, $matches);
                            if (!empty($matches[1])) {
                                foreach ($matches[1] as $idx => $num) {
                                    $gGoals[] = [
                                        'num' => trim($num),
                                        'text' => trim($matches[2][$idx])
                                    ];
                                }
                            }
                        }
                    @endphp
                    <div class="col-span-1 md:col-span-1 lg:col-span-3 rounded-3xl border border-brand-accent/20 bg-white dark:bg-gray-800 p-8 shadow-sm hover:shadow-md transition-all duration-300 ease-out hover:-translate-y-1 active:scale-[0.99] active:shadow-sm focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-primary/40 focus-visible:ring-offset-2 dark:focus-visible:ring-offset-gray-900 transform cursor-default select-none motion-reduce:transition-none motion-reduce:transform-none w-full group"
                         tabindex="0"
                         :class="inView ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-6'"
                         style="transition-delay: 200ms">
                        
                        <div class="flex flex-row items-start gap-6 w-full">
                            
                            {{-- Text content (First in LTR layout) --}}
                            <div class="space-y-3 flex-1 w-full">
                                <h3 class="text-xl font-bold text-brand-text dark:text-brand-bg leading-snug">
                                    {{ $gTitle }}
                                </h3>
                                @if (!empty($gGoals))
                                    <ul class="space-y-4 mt-4 w-full">
                                        @foreach ($gGoals as $item)
                                            <li class="flex items-start gap-4">
                                                <span class="flex items-center justify-center shrink-0 w-7 h-7 rounded-full bg-brand-primary/10 text-brand-primary text-xs font-bold font-mono mt-0.5 shadow-sm">
                                                    {{ $item['num'] }}
                                                </span>
                                                <span class="text-base text-brand-text/70 dark:text-gray-300 leading-relaxed font-sans flex-1">
                                                    {{ $item['text'] }}
                                                </span>
                                            </li>
                                        @endforeach
                                    </ul>
                                @else
                                    <p class="text-base text-brand-text/70 dark:text-gray-300 leading-relaxed font-sans max-w-prose">
                                        {{ $gDesc }}
                                    </p>
                                @endif
                            </div>

                            {{-- Image Container (Second in LTR layout) --}}
                            @if ($gImg)
                                <div class="w-14 h-14 rounded-2xl bg-brand-secondary/40 dark:bg-brand-accent/20 flex items-center justify-center shrink-0 transition-all duration-300 group-hover:bg-brand-secondary/60 dark:group-hover:bg-brand-accent/30 shadow-sm p-2">
                                    <img src="{{ $gImg }}" alt="{{ $gTitle }}" class="w-full h-full object-contain transition-transform duration-200 group-hover:scale-105" loading="lazy">
                                </div>
                            @endif

                        </div>
                    </div>
                @endif

                <!-- Values: 40% Width (lg:col-span-2) -->
                @if ($valuesSection)
                    @php
                        $vaTitle = $locale === 'ar' ? $valuesSection->title_ar : ($valuesSection->title_en ?? $valuesSection->title_ar);
                        $vaDesc  = $locale === 'ar' ? $valuesSection->description_ar : ($valuesSection->description_en ?? $valuesSection->description_ar);
                        $vaImg   = \App\Helpers\MediaHelper::url($valuesSection, 'about_images', 'image', 'thumb');
                        
                        $vaValues = [];
                        if (!empty($vaDesc)) {
                            $lines = preg_split('/\r\n|\r|\n/', trim($vaDesc));
                            $lines = array_map('trim', $lines);
                            $lines = array_filter($lines);
                            $lines = array_values($lines);
                            
                            for ($i = 0; $i < count($lines); $i += 2) {
                                if (isset($lines[$i])) {
                                    $vaValues[] = [
                                        'title' => $lines[$i],
                                        'desc' => $lines[$i+1] ?? ''
                                    ];
                                }
                            }
                        }
                    @endphp
                    <div class="col-span-1 md:col-span-1 lg:col-span-2 rounded-3xl border border-brand-accent/20 bg-white dark:bg-gray-800 p-8 shadow-sm hover:shadow-md transition-all duration-300 ease-out hover:-translate-y-1 active:scale-[0.99] active:shadow-sm focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-primary/40 focus-visible:ring-offset-2 dark:focus-visible:ring-offset-gray-900 transform cursor-default select-none motion-reduce:transition-none motion-reduce:transform-none w-full group"
                         tabindex="0"
                         :class="inView ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-6'"
                         style="transition-delay: 300ms">
                        
                        <div class="flex flex-row items-start gap-6 w-full">
                            
                            {{-- Text content (First in LTR layout) --}}
                            <div class="space-y-3 flex-1 w-full">
                                <h3 class="text-xl font-bold text-brand-text dark:text-brand-bg leading-snug">
                                    {{ $vaTitle }}
                                </h3>
                                @if (!empty($vaValues))
                                    <div class="space-y-4 mt-4 w-full">
                                        @foreach ($vaValues as $val)
                                            <div class="space-y-1">
                                                <h4 class="text-base font-bold text-brand-primary dark:text-brand-accent">
                                                    {{ $val['title'] }}
                                                </h4>
                                                <p class="text-sm text-brand-text/70 dark:text-gray-300 leading-relaxed font-sans max-w-prose">
                                                    {{ $val['desc'] }}
                                                </p>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="text-base text-brand-text/70 dark:text-gray-300 leading-relaxed font-sans max-w-prose">
                                        {!! nl2br(e($vaDesc)) !!}
                                    </div>
                                @endif
                            </div>

                            {{-- Image Container (Second in LTR layout) --}}
                            @if ($vaImg)
                                <div class="w-14 h-14 rounded-2xl bg-brand-secondary/40 dark:bg-brand-accent/20 flex items-center justify-center shrink-0 transition-all duration-300 group-hover:bg-brand-secondary/60 dark:group-hover:bg-brand-accent/30 shadow-sm p-2">
                                    <img src="{{ $vaImg }}" alt="{{ $vaTitle }}" class="w-full h-full object-contain transition-transform duration-200 group-hover:scale-105" loading="lazy">
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

            </div>

        </div>
    </section>
    <!-- 4. Dynamic Extra Sections -->
    @foreach ($otherSections as $section)
        @php
            $title  = $locale === 'ar' ? $section->title_ar : ($section->title_en ?? $section->title_ar);
            $desc   = $locale === 'ar' ? $section->description_ar : ($section->description_en ?? $section->description_ar);
            $img    = \App\Helpers\MediaHelper::url($section, 'about_images', 'image', 'detail');
            $isEven = $loop->even;
        @endphp

        <section class="py-12 md:py-16 border-t border-brand-accent/10 overflow-hidden"
                 x-data="{ inView: false }"
                 x-intersect.once="inView = true">
            
            @if ($isEven)
                <div class="rounded-3xl border border-brand-accent/15 bg-brand-secondary/5 dark:bg-gray-800/10 p-6 sm:p-8 md:p-12 shadow-sm transition-all duration-500 ease-out transform"
                     :class="inView ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-4'">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-12 items-center">
                        
                        {{-- Text Content --}}
                        <div class="space-y-6 transition-all duration-700 ease-out transform motion-reduce:transition-none motion-reduce:transform-none"
                             :class="inView ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-6'">
                            @if ($title)
                                <h2 class="text-2xl sm:text-3xl font-bold text-brand-text dark:text-brand-bg leading-snug border-s-4 border-brand-primary ps-4">
                                    {{ $title }}
                                </h2>
                            @endif

                            @if ($desc)
                                <div class="text-base text-brand-text/70 dark:text-gray-300 leading-relaxed space-y-4 font-sans">
                                    {!! nl2br(e($desc)) !!}
                                </div>
                            @endif

                            <div class="pt-2">
                                <x-frontend.external-link-button :model="$section" collection="about_images" class="!px-5 !py-2.5 !text-xs active:scale-[0.98]" />
                            </div>
                        </div>

                        {{-- Image Content --}}
                        @if ($img)
                            <div class="relative overflow-hidden rounded-2xl group shadow-lg transition-all duration-700 ease-out transform motion-reduce:transition-none motion-reduce:transform-none"
                                 :class="inView ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-6'"
                                 style="transition-delay: 150ms">
                                <div class="aspect-[4/3] w-full bg-[#FDFEF6] dark:bg-gray-800">
                                    <img src="{{ $img }}" alt="{{ $title ?? '' }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-[1.02]" loading="lazy">
                                </div>
                            </div>
                        @endif

                    </div>
                </div>
            @else
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-12 items-center">
                    
                    {{-- Text Content --}}
                    <div class="space-y-6 transition-all duration-700 ease-out transform motion-reduce:transition-none motion-reduce:transform-none"
                         :class="inView ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-6'">
                        @if ($title)
                            <h2 class="text-2xl sm:text-3xl font-bold text-brand-text dark:text-brand-bg leading-snug border-s-4 border-brand-primary ps-4">
                                {{ $title }}
                            </h2>
                        @endif

                        @if ($desc)
                            <div class="text-base text-brand-text/70 dark:text-gray-300 leading-relaxed space-y-4 font-sans">
                                {!! nl2br(e($desc)) !!}
                            </div>
                        @endif

                        <div class="pt-2">
                            <x-frontend.external-link-button :model="$section" collection="about_images" class="!px-5 !py-2.5 !text-xs active:scale-[0.98]" />
                        </div>
                    </div>

                    {{-- Image Content --}}
                    @if ($img)
                        <div class="relative overflow-hidden rounded-2xl group shadow-lg transition-all duration-700 ease-out transform motion-reduce:transition-none motion-reduce:transform-none"
                             :class="inView ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-6'"
                             style="transition-delay: 150ms">
                            <div class="aspect-[4/3] w-full bg-[#FDFEF6] dark:bg-gray-800">
                                <img src="{{ $img }}" alt="{{ $title ?? '' }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-[1.02]" loading="lazy">
                            </div>
                        </div>
                    @endif

                </div>
            @endif
        </section>
    @endforeach

    <!-- Leadership & Team CTA Block -->
    <div class="mt-24 pt-16 border-t border-brand-accent/20 text-center max-w-2xl mx-auto space-y-6"
         x-data="{ inView: false }"
         x-intersect.once="inView = true"
         :class="inView ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-4'"
         class="transition-all duration-500 ease-out transform">
        <h3 class="text-xl sm:text-2xl font-bold text-brand-text dark:text-gray-100">
            {{ $locale === 'ar' ? 'فريق العمل والقيادة' : 'Our Team & Leadership' }}
        </h3>
        <p class="text-sm text-brand-text/70 dark:text-gray-400 max-w-md mx-auto leading-relaxed">
            {{ $locale === 'ar' ? 'تعرف على مجلس الأمناء والفريق التنفيذي الذي يقود مسيرة الأثر والتنمية.' : 'Meet our board of directors and executive team leading our journey of development and impact.' }}
        </p>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 max-w-md mx-auto pt-2">
            <x-frontend.button :href="route('about.board')" variant="primary" class="w-full justify-center py-3.5 rounded-2xl shadow-md hover:shadow-lg transition-all duration-300 active:scale-[0.98]">
                {{ __('frontend.board_of_directors') }}
            </x-frontend.button>
            <x-frontend.button :href="route('about.executive-team')" variant="outline" class="w-full justify-center py-3.5 rounded-2xl hover:bg-brand-primary/10 transition-all duration-300 active:scale-[0.98]">
                {{ __('frontend.executive_team') }}
            </x-frontend.button>
        </div>
    </div>

</x-frontend-layout>
