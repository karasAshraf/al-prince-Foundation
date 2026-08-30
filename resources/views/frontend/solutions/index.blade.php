<x-frontend-layout title="{{ __('frontend.solutions') }}">

    <!-- Page Header -->
    <div class="text-center mb-16">
        <x-frontend.badge variant="secondary">{{ __('frontend.our_solutions') }}</x-frontend.badge>
        <h1 class="text-3xl sm:text-4xl font-bold text-text-primary dark:text-surface mt-3 leading-tight">
            {{ __('frontend.solutions_title') }}
        </h1>
        <p class="mt-4 text-text-primary/70 dark:text-gray-400 max-w-xl mx-auto">
            {{ __('frontend.solutions_page_desc') }}
        </p>
    </div>

    @if ($solutions->isNotEmpty())
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-4xl mx-auto"
             x-data="{ inView: false }"
             x-intersect.once="inView = true">
            @foreach ($solutions as $index => $solution)
                @php
                    $locale    = app()->getLocale();
                    $title     = $locale === 'ar' ? $solution->title_ar : ($solution->title_en ?? $solution->title_ar);
                    $desc      = $locale === 'ar' ? $solution->description_ar : ($solution->description_en ?? $solution->description_ar);
                    $img       = \App\Helpers\MediaHelper::url($solution, 'solution_images', 'image', 'card');
                    
                    if ($solution->id == 2) {
                        $targetUrl = route('solutions.developmental');
                        $ctaText = $locale === 'ar' ? 'استكشف الحلول التنموية' : 'Explore Developmental Solutions';
                    } else {
                        $targetUrl = route('solutions.digital-technical');
                        $ctaText = $locale === 'ar' ? 'استكشف الحلول الرقمية والفنية' : 'Explore Digital & Technical Solutions';
                    }
                @endphp

                <!-- Staggered Animated Card -->
                <div class="transition-all duration-700 ease-out transform motion-reduce:transition-none motion-reduce:transform-none"
                     :class="inView ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-8'"
                     style="transition-delay: {{ $index * 150 }}ms">
                    <x-frontend.card :hoverable="true" :padding="'none'" class="flex flex-col justify-between h-full group">
                        @if ($img)
                            <div class="overflow-hidden relative aspect-video">
                                <a href="{{ $targetUrl }}" class="block">
                                    <img src="{{ $img }}" alt="{{ $title }}" loading="eager"
                                         class="w-full h-full object-cover transform scale-100 group-hover:scale-103 transition-transform duration-700 ease-out">
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none"></div>
                                </a>
                            </div>
                        @endif

                        <div class="p-6 flex-1 flex flex-col justify-between">
                            <div class="space-y-3">
                                <h3 class="text-xl font-bold text-text-primary dark:text-gray-100 leading-snug group-hover:text-primary transition-colors duration-200">
                                    <a href="{{ $targetUrl }}" class="focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary rounded-md">
                                        {{ $title }}
                                    </a>
                                </h3>

                                @if ($desc)
                                    <p class="text-sm text-text-primary/75 dark:text-gray-300 leading-relaxed font-normal">
                                        {{ $desc }}
                                    </p>
                                @endif
                            </div>

                            <div class="pt-6 border-t border-primary-light/10 mt-6">
                                <x-frontend.button :href="$targetUrl" variant="outline" size="md" class="w-full justify-center group-hover:bg-primary group-hover:text-white transition-all duration-300">
                                    <span>{{ $ctaText }}</span>
                                    <svg class="w-4 h-4 inline-block ms-1.5 transform transition-transform duration-300 group-hover:translate-x-1 rtl:group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                                    </svg>
                                </x-frontend.button>
                            </div>
                        </div>
                    </x-frontend.card>
                </div>
            @endforeach
        </div>
    @else
        <x-frontend.empty-state
            :title="__('frontend.no_solutions_available')"
            :description="__('frontend.solutions_coming_soon')"
        />
    @endif

</x-frontend-layout>
