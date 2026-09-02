
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
        $valuesSection = $aboutSections->first(fn($s) => $s->slug === 'kymna');
 
        // 3. Collect any other sections
        $excludeSlugs = ['nbth', 'altasys', 'roytna', 'rsaltna', 'ahdafna', 'kymna', 'test-about'];
        $otherSections = $aboutSections->reject(fn($s) => in_array($s->slug, $excludeSlugs));
 
        // Custom easing used throughout this page for a more polished feel
        // than Tailwind's default ease-out.
        $easeSoft = '[transition-timing-function:cubic-bezier(0.4,0,0.2,1)]';
    @endphp
 
    {{-- ============================================================
         UNIFIED PAGE BACKGROUND — single consistent canvas (#E1DFDD)
         for the entire page. Individual cards use White (#F5F5F5) or
         Light Gray (#EAEAE9) only as foreground surfaces, never as a
         competing section-level background.
         ============================================================ --}}
    <div class="bg-background">
 
        {{-- ============================================================
             1. OVERVIEW SECTION ("نبذة")
             ============================================================ --}}
        @if ($overviewSection)
            @php
                $oTitle = $locale === 'ar' ? $overviewSection->title_ar : ($overviewSection->title_en ?? $overviewSection->title_ar);
                $oDesc  = $locale === 'ar' ? $overviewSection->description_ar : ($overviewSection->description_en ?? $overviewSection->description_ar);
                $oImg   = \App\Helpers\MediaHelper::url($overviewSection, 'about_images', 'image', 'detail');
            @endphp
            <section class="py-14 md:py-20 overflow-hidden"
                     x-data="{ inView: false }"
                     x-intersect.once="inView = true">
                <x-frontend.container>
                    <div class="flex flex-col lg:flex-row items-center gap-8 lg:gap-14">
 
                        {{-- Text Content --}}
                        <div class="w-full lg:w-[58%] space-y-4 transition-all duration-700 {{ $easeSoft }} transform motion-reduce:transition-none motion-reduce:transform-none"
                             :class="inView ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-6'">
                            <span class="text-sm font-semibold text-primary tracking-wider uppercase block">
                                {{ $locale === 'ar' ? 'عن المؤسسة' : 'About Foundation' }}
                            </span>
                            <h2 class="text-2xl sm:text-3xl font-bold text-text-primary leading-snug">
                                {{ $oTitle }}
                            </h2>
                            <div class="text-base leading-relaxed text-text-primary max-w-prose space-y-4">
                                {!! nl2br(e($oDesc)) !!}
                            </div>
                            <div class="pt-2">
                                <x-frontend.external-link-button :model="$overviewSection" collection="about_images"
                                    class="!px-5 !py-2.5 !text-xs !bg-primary hover:!bg-accent active:scale-[0.98] transition-all duration-300 {{ $easeSoft }}" />
                            </div>
                        </div>
 
                        {{-- Image Container --}}
                        @if ($oImg)
                            <div class="w-full lg:w-[42%] shrink-0 flex items-center justify-center transition-all duration-700 {{ $easeSoft }} transform motion-reduce:transition-none motion-reduce:transform-none"
                                 :class="inView ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-6'"
                                 style="transition-delay: 150ms">
                                <div class="relative w-full max-w-[360px] aspect-square flex items-center justify-center">
                                    <img src="{{ $oImg }}" alt="{{ $oTitle }}"
                                         class="w-[200px] h-[200px] sm:w-[240px] sm:h-[240px] lg:w-[280px] lg:h-[280px] object-contain transition-transform duration-500 hover:scale-[1.03]"
                                         loading="lazy">
                                </div>
                            </div>
                        @endif
 
                    </div>
                </x-frontend.container>
            </section>
        @endif
 
        {{-- ============================================================
             2. ESTABLISHMENT SECTION ("التأسيس")
             ============================================================ --}}
        @if ($establishmentSection)
            @php
                $eTitle = $locale === 'ar' ? $establishmentSection->title_ar : ($establishmentSection->title_en ?? $establishmentSection->title_ar);
                $eDesc  = $locale === 'ar' ? $establishmentSection->description_ar : ($establishmentSection->description_en ?? $establishmentSection->description_ar);
                $eImg   = \App\Helpers\MediaHelper::url($establishmentSection, 'about_images', 'image', 'detail');
            @endphp
            <section class="py-14 md:py-20 border-t border-secondary/60 overflow-hidden"
                     x-data="{ inView: false }"
                     x-intersect.once="inView = true">
                <x-frontend.container>
                    <div class="rounded-3xl border border-secondary bg-background p-6 sm:p-8 md:p-12 shadow-sm transition-all duration-500 {{ $easeSoft }} transform"
                         :class="inView ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-4'">
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-14 items-center">
 
                            <div class="space-y-4 transition-all duration-700 {{ $easeSoft }} transform motion-reduce:transition-none motion-reduce:transform-none"
                                 :class="inView ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-6'">
                                <span class="text-sm font-semibold text-primary tracking-wider uppercase block">
                                    {{ $locale === 'ar' ? 'التأسيس' : 'Establishment' }}
                                </span>
                                <h2 class="text-2xl sm:text-3xl font-bold text-text-primary leading-snug">
                                    {{ $eTitle }}
                                </h2>
                                <div class="text-base leading-relaxed text-text-primary max-w-prose space-y-4">
                                    {!! nl2br(e($eDesc)) !!}
                                </div>
                                <div class="pt-2">
                                    <x-frontend.external-link-button :model="$establishmentSection" collection="about_images"
                                        class="!px-5 !py-2.5 !text-xs !bg-primary hover:!bg-accent active:scale-[0.98] transition-all duration-300 {{ $easeSoft }}" />
                                </div>
                            </div>
 
                            @if ($eImg)
                                <div class="relative overflow-hidden rounded-2xl group transition-all duration-700 {{ $easeSoft }} transform motion-reduce:transition-none motion-reduce:transform-none"
                                     :class="inView ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-6'"
                                     style="transition-delay: 150ms">
                                    <div class="aspect-[4/3] w-full bg-background">
                                        <img src="{{ $eImg }}" alt="{{ $eTitle }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-[1.02]" loading="lazy">
                                    </div>
                                </div>
                            @endif
 
                        </div>
                    </div>
                </x-frontend.container>
            </section>
        @endif
 
        {{-- ============================================================
             3. PILLARS — Vision / Mission / Goals / Values (Zigzag Layout)
             ============================================================ --}}
        <section class="py-14 md:py-16 border-t border-secondary/40">
            <x-frontend.container>
                <div class="space-y-12 md:space-y-16 max-w-6xl mx-auto">
 
                    @php
                        $pillars = collect([
                            ['section' => $visionSection, 'icon_label' => $locale === 'ar' ? 'رؤيتنا' : 'Our Vision'],
                            ['section' => $missionSection, 'icon_label' => $locale === 'ar' ? 'رسالتنا' : 'Our Mission'],
                            ['section' => $goalsSection, 'icon_label' => $locale === 'ar' ? 'أهدافنا' : 'Our Goals'],
                            ['section' => $valuesSection, 'icon_label' => $locale === 'ar' ? 'قيمنا' : 'Our Values'],
                        ])->filter(fn($p) => $p['section']);
                    @endphp
 
                    @foreach ($pillars as $pillar)
                        @php
                            $section = $pillar['section'];
                            $pTitle = $locale === 'ar' ? $section->title_ar : ($section->title_en ?? $section->title_ar);
                            $pDesc  = $locale === 'ar' ? $section->description_ar : ($section->description_en ?? $section->description_ar);
                            $pImg   = \App\Helpers\MediaHelper::url($section, 'about_images', 'image', 'thumb');
                            $isEven = $loop->even;
 
                            // Goals: numbered list detection
                            $listItems = [];
                            if ($section->slug === 'ahdafna' && !empty($pDesc)) {
                                preg_match_all('/([1-9]\d*)\s*[\t\.\-]?\s*(.+?)(?=\s*[1-9]\d*\s*[\t\.\-]?\s*|$)/us', $pDesc, $matches);
                                if (!empty($matches[1])) {
                                    foreach ($matches[1] as $idx => $num) {
                                        $listItems[] = ['num' => trim($num), 'text' => trim($matches[2][$idx])];
                                    }
                                }
                            }
 
                            // Values: title/desc pairs on alternating lines
                            $valuePairs = [];
                            if ($section->slug === 'kymna' && !empty($pDesc)) {
                                $lines = array_values(array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', trim($pDesc)))));
                                for ($j = 0; $j < count($lines); $j += 2) {
                                    if (isset($lines[$j])) {
                                        $valuePairs[] = ['title' => $lines[$j], 'desc' => $lines[$j + 1] ?? ''];
                                    }
                                }
                            }
                        @endphp
 
                        <div class="transition-all duration-700 {{ $easeSoft }} transform motion-reduce:transition-none motion-reduce:transform-none select-none"
                             x-data="{ inView: false }"
                             x-intersect.once="inView = true"
                             :class="inView ? 'opacity-100 scale-100' : 'opacity-0 scale-95'"
                             style="transition-delay: {{ $loop->index * 150 }}ms">
                            
                            <div class="flex flex-col lg:flex-row items-center gap-8 lg:gap-14 {{ $isEven ? 'lg:flex-row-reverse' : '' }}">
                                
                                {{-- Image Container --}}
                                @if ($pImg)
                                    <div class="w-full lg:w-[42%] shrink-0 flex items-center justify-center">
                                        <div class="relative w-full max-w-[360px] aspect-square flex items-center justify-center">
                                            <img src="{{ $pImg }}" alt="{{ $pTitle }}" class="w-[200px] h-[200px] sm:w-[240px] sm:h-[240px] lg:w-[280px] lg:h-[280px] object-contain transition-transform duration-500 hover:scale-[1.03]" loading="lazy">
                                        </div>
                                    </div>
                                @endif
 
                                {{-- Text Content --}}
                                <div class="w-full lg:w-[58%] space-y-4 text-center lg:text-start">
                                    <span class="text-sm font-semibold text-primary tracking-wider uppercase block">
                                        {{ $pillar['icon_label'] }}
                                    </span>
                                    
                                    <h3 class="relative inline-block text-2xl font-bold text-text-primary leading-snug group/title cursor-default">
                                        <span class="relative py-1 after:absolute after:bottom-0 after:start-0 after:w-full after:h-[2px] after:bg-primary after:scale-x-0 group-hover/title:after:scale-x-100 after:transition-transform after:duration-300 after:origin-left rtl:after:origin-right">
                                            {{ $pTitle }}
                                        </span>
                                    </h3>
 
                                    @if (!empty($listItems))
                                        <ul class="space-y-4 mt-4 w-full text-start">
                                            @foreach ($listItems as $item)
                                                <li class="flex items-start gap-4">
                                                    <span class="flex items-center justify-center shrink-0 w-7 h-7 rounded-full bg-primary/10 text-primary text-xs font-bold font-mono mt-0.5 shadow-sm">
                                                        {{ $item['num'] }}
                                                    </span>
                                                    <span class="text-base text-text-primary leading-relaxed font-sans flex-1">
                                                        {{ $item['text'] }}
                                                    </span>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @elseif (!empty($valuePairs))
                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mt-4 w-full text-start">
                                            @foreach ($valuePairs as $val)
                                                <div class="space-y-1 group/val cursor-default">
                                                    <h4 class="relative inline-block text-base font-bold text-primary group-hover/val:text-primary transition-colors duration-300">
                                                        <span class="relative py-0.5 after:absolute after:bottom-0 after:start-0 after:w-full after:h-[1.5px] after:bg-primary after:scale-x-0 group-hover/val:after:scale-x-100 after:transition-transform after:duration-300 after:origin-left rtl:after:origin-right">
                                                            {{ $val['title'] }}
                                                        </span>
                                                    </h4>
                                                    <p class="text-sm text-text-primary leading-relaxed font-sans">
                                                        {{ $val['desc'] }}
                                                    </p>
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <p class="text-base text-text-primary leading-relaxed font-sans max-w-prose text-start">
                                            {{ $pDesc }}
                                        </p>
                                    @endif
                                </div>
 
                            </div>
                        </div>
 
                        {{-- Thin separator except for last item --}}
                        @if (!$loop->last)
                            <div class="border-t border-secondary/40 my-6"></div>
                        @endif
 
                    @endforeach
 
                </div>
            </x-frontend.container>
        </section>
 
        {{-- ============================================================
             4. DYNAMIC EXTRA SECTIONS
             ============================================================ --}}
        @foreach ($otherSections as $section)
            @php
                $title  = $locale === 'ar' ? $section->title_ar : ($section->title_en ?? $section->title_ar);
                $desc   = $locale === 'ar' ? $section->description_ar : ($section->description_en ?? $section->description_ar);
                $img    = \App\Helpers\MediaHelper::url($section, 'about_images', 'image', 'detail');
            @endphp
 
            <section class="py-14 md:py-20 border-t border-secondary/60 overflow-hidden"
                     x-data="{ inView: false }"
                     x-intersect.once="inView = true">
                <x-frontend.container>
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-14 items-center">
 
                        <div class="space-y-6 transition-all duration-700 {{ $easeSoft }} transform motion-reduce:transition-none motion-reduce:transform-none"
                             :class="inView ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-6'">
                            @if ($title)
                                <h2 class="text-2xl sm:text-3xl font-bold text-text-primary leading-snug border-s-4 border-primary ps-4">
                                    {{ $title }}
                                </h2>
                            @endif
 
                            @if ($desc)
                                <div class="text-base text-text-primary leading-relaxed space-y-4 font-sans">
                                    {!! nl2br(e($desc)) !!}
                                </div>
                            @endif
 
                            <div class="pt-2">
                                <x-frontend.external-link-button :model="$section" collection="about_images"
                                    class="!px-5 !py-2.5 !text-xs !bg-primary hover:!bg-accent active:scale-[0.98] transition-all duration-300 {{ $easeSoft }}" />
                            </div>
                        </div>
 
                        @if ($img)
                            <div class="relative overflow-hidden rounded-2xl border border-secondary shadow-lg transition-all duration-700 {{ $easeSoft }} transform motion-reduce:transition-none motion-reduce:transform-none"
                                 :class="inView ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-6'"
                                 style="transition-delay: 150ms">
                                <div class="aspect-[4/3] w-full bg-background">
                                    <img src="{{ $img }}" alt="{{ $title ?? '' }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-[1.02]" loading="lazy">
                                </div>
                            </div>
                        @endif
 
                    </div>
                </x-frontend.container>
            </section>
        @endforeach
 
        {{-- ============================================================
             5. LEADERSHIP & TEAM CTA
             ============================================================ --}}
        <section class="py-16 md:py-20 border-t border-secondary/60"
                 x-data="{ inView: false }"
                 x-intersect.once="inView = true">
            <x-frontend.container>
                <div class="text-center max-w-2xl mx-auto space-y-6 transition-all duration-500 {{ $easeSoft }} transform"
                     :class="inView ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-4'">
                    <h3 class="text-xl sm:text-2xl font-bold text-text-primary">
                        {{ $locale === 'ar' ? 'فريق العمل والقيادة' : 'Our Team & Leadership' }}
                    </h3>
                    <p class="text-sm text-text-primary max-w-md mx-auto leading-relaxed">
                        {{ $locale === 'ar' ? 'تعرف على مجلس الأمناء والفريق التنفيذي الذي يقود مسيرة الأثر والتنمية.' : 'Meet our board of directors and executive team leading our journey of development and impact.' }}
                    </p>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 max-w-md mx-auto pt-2">
                        <x-frontend.button :href="route('about.board')" variant="primary"
                            class="w-full justify-center py-3.5 rounded-2xl shadow-md hover:shadow-lg !bg-primary hover:!bg-accent transition-all duration-300 {{ $easeSoft }} active:scale-[0.98]">
                            {{ __('frontend.board_of_directors') }}
                        </x-frontend.button>
                        <x-frontend.button :href="route('about.executive-team')" variant="outline"
                            class="w-full justify-center py-3.5 rounded-2xl !border-primary !text-primary hover:!bg-primary/10 transition-all duration-300 {{ $easeSoft }} active:scale-[0.98]">
                            {{ __('frontend.executive_team') }}
                        </x-frontend.button>
                    </div>
                </div>
            </x-frontend.container>
        </section>
 
    </div>
 
</x-frontend-layout>
