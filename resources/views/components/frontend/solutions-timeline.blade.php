@props([
    'solutions',
    'iconsMap' => null,
    'isTech' => false,
])

@php
    $locale = app()->getLocale();

    $toArabicIndic = function($num) {
        $arabic = ['٠','١','٢','٣','٤','٥','٦','٧','٨','٩'];
        return str_replace(range(0, 9), $arabic, $num);
    };
@endphp

<div class="relative {{ $isTech ? 'max-w-4xl' : 'max-w-5xl' }} mx-auto py-12 px-4 sm:px-6 overflow-hidden">
    
    <!-- Central vertical connecting line -->
    <div class="absolute top-0 bottom-0 start-12 -translate-x-1/2 rtl:translate-x-1/2 md:start-1/2 md:-translate-x-1/2 md:rtl:translate-x-1/2 w-0.5 bg-primary-light/25"></div>

    <div class="space-y-16 md:space-y-24">

        @foreach ($solutions->values() as $index => $solution)
            @php
                $title = $locale === 'ar' ? $solution->title_ar : ($solution->title_en ?? $solution->title_ar);
                $desc  = $locale === 'ar' ? $solution->description_ar : ($solution->description_en ?? $solution->description_ar);
                
                // Determine icon or image source
                $icon = $iconsMap[$solution->slug] ?? null;
                $img = !$icon ? \App\Helpers\MediaHelper::url($solution, 'solution_images', 'image', 'card') : null;
                
                $isEven = ($index % 2 === 0);
                $numFormatted = sprintf('%02d', $index + 1);
                $numEyebrow = $locale === 'ar' ? $toArabicIndic($numFormatted) : $numFormatted;
                $numDisplay = $locale === 'ar' ? $toArabicIndic($index + 1) : ($index + 1);

                $detailUrl = $solution->external_link ?: route('solutions.show', $solution->slug);
                $isExternal = (bool) $solution->external_link;
            @endphp

            <div class="relative flex flex-col md:flex-row items-center justify-between gap-8 md:gap-16 w-full group"
                 x-data="{ inView: false }"
                 x-intersect.once="inView = true">
                 
                <!-- Number Circle Badge -->
                <div class="w-14 h-14 rounded-full bg-primary text-white font-bold text-lg flex items-center justify-center shadow-md absolute z-20 start-12 -translate-x-1/2 rtl:translate-x-1/2 md:start-1/2 md:-translate-x-1/2 md:rtl:translate-x-1/2 top-12 -translate-y-1/2 md:top-1/2 md:-translate-y-1/2 transition-transform duration-500 scale-0 motion-reduce:transition-none"
                     :class="inView ? 'scale-100' : 'scale-0'"
                     style="transition-delay: 100ms;">
                    {{ $numDisplay }}
                </div>

                @if ($isEven)
                    {{-- Even index row (Icon/Image Left, Text Right in LTR) --}}
                    <!-- Icon/Image Column -->
                    <div class="w-full md:w-[calc(50%-3rem)] flex justify-start md:justify-end ps-20 md:ps-0 order-1 md:order-none transition-transform duration-500 scale-0 motion-reduce:transition-none"
                         :class="inView ? 'scale-100' : 'scale-0'"
                         style="transition-delay: 150ms;">
                        <a href="{{ $detailUrl }}" {{ $isExternal ? 'target="_blank" rel="noopener"' : '' }} class="{{ $isTech ? 'w-24 h-24 sm:w-28 sm:h-28' : 'w-20 h-20 sm:w-24 sm:h-24' }} rounded-full bg-gradient-to-br from-secondary-light/40 to-primary-light/20 flex items-center justify-center shadow-sm shrink-0 overflow-hidden p-0 hover:scale-105 transition-transform duration-300">
                            @if ($icon)
                                <x-icon :name="$icon" class="w-10 h-10 text-primary" fallback="sparkles" />
                            @elseif ($img)
                                <img src="{{ $img }}" alt="{{ $title }}" class="w-[80%] h-[80%] object-contain" loading="lazy">
                            @else
                                <x-icon name="sparkles" class="w-10 h-10 text-primary" />
                            @endif
                        </a>
                    </div>

                    <!-- Text Content Column -->
                    <div class="w-full md:w-[calc(50%-3rem)] space-y-3 ps-20 md:ps-0 order-2 md:order-none transition-all duration-700 ease-out transform translate-y-8 opacity-0 motion-reduce:transition-none motion-reduce:transform-none"
                         :class="inView ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-8'">
                        <div class="flex items-center gap-3">
                            <span class="text-sm font-semibold text-primary tracking-wide uppercase block">
                                {{ $numEyebrow }}
                            </span>
                            @if ($isTech)
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-semibold tracking-wide bg-[#3D342A]/85 text-[#EAEAE9] border border-[#EAEAE9]/20">
                                    {{ $locale === 'ar' ? 'رقمي' : 'Digital' }}
                                </span>
                            @endif
                        </div>
                        <h3 class="text-xl sm:text-2xl font-bold text-text-primary leading-snug hover:text-primary transition-colors">
                            <a href="{{ $detailUrl }}" {{ $isExternal ? 'target="_blank" rel="noopener"' : '' }}>
                                {{ $title }}
                            </a>
                        </h3>
                        <p class="text-base text-text-primary/70 leading-relaxed font-sans max-w-prose">
                            {{ $desc }}
                        </p>
                    </div>
                @else
                    {{-- Odd index row (Text Left, Icon/Image Right in LTR) --}}
                    <!-- Text Content Column -->
                    <div class="w-full md:w-[calc(50%-3rem)] space-y-3 ps-20 md:ps-0 order-2 md:order-none transition-all duration-700 ease-out transform translate-y-8 opacity-0 motion-reduce:transition-none motion-reduce:transform-none"
                         :class="inView ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-8'">
                        <div class="flex items-center gap-3">
                            <span class="text-sm font-semibold text-primary tracking-wide uppercase block">
                                {{ $numEyebrow }}
                            </span>
                            @if ($isTech)
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-semibold tracking-wide bg-[#3D342A]/85 text-[#EAEAE9] border border-[#EAEAE9]/20">
                                    {{ $locale === 'ar' ? 'رقمي' : 'Digital' }}
                                </span>
                            @endif
                        </div>
                        <h3 class="text-xl sm:text-2xl font-bold text-text-primary leading-snug hover:text-primary transition-colors">
                            <a href="{{ $detailUrl }}" {{ $isExternal ? 'target="_blank" rel="noopener"' : '' }}>
                                {{ $title }}
                            </a>
                        </h3>
                        <p class="text-base text-text-primary/70 leading-relaxed font-sans max-w-prose">
                            {{ $desc }}
                        </p>
                    </div>

                    <!-- Icon/Image Column -->
                    <div class="w-full md:w-[calc(50%-3rem)] flex justify-start ps-20 md:ps-0 order-1 md:order-none transition-transform duration-500 scale-0 motion-reduce:transition-none"
                         :class="inView ? 'scale-100' : 'scale-0'"
                         style="transition-delay: 150ms;">
                        <a href="{{ $detailUrl }}" {{ $isExternal ? 'target="_blank" rel="noopener"' : '' }} class="{{ $isTech ? 'w-24 h-24 sm:w-28 sm:h-28' : 'w-20 h-20 sm:w-24 sm:h-24' }} rounded-full bg-gradient-to-br from-secondary-light/40 to-primary-light/20 flex items-center justify-center shadow-sm shrink-0 overflow-hidden p-0 hover:scale-105 transition-transform duration-300">
                            @if ($icon)
                                <x-icon :name="$icon" class="w-10 h-10 text-primary" fallback="sparkles" />
                            @elseif ($img)
                                <img src="{{ $img }}" alt="{{ $title }}" class="w-[80%] h-[80%] object-contain" loading="lazy">
                            @else
                                <x-icon name="sparkles" class="w-10 h-10 text-primary" />
                            @endif
                        </a>
                    </div>
                @endif

            </div>
        @endforeach

    </div>
</div>
