@props([
    'services' => null,
    'section'  => null,
])

@php
    $locale       = app()->getLocale();
    $sectionTitle = $section
        ? ($locale === 'ar' ? $section->title_ar : ($section->title_en ?? $section->title_ar))
        : null;
    $sectionDesc  = $section
        ? ($locale === 'ar' ? $section->description_ar : ($section->description_en ?? $section->description_ar))
        : null;
@endphp

<section {{ $attributes->merge(['class' => 'py-12 md:py-20']) }}
         x-data="{ inView: false }"
         x-intersect.once="inView = true">
    <div class="transition-all duration-1000 transform"
         :class="inView ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-8'">
        <x-frontend.section-title
            badge="{{ __('frontend.our_services') }}"
            :title="$sectionTitle ?: __('frontend.our_services_title')"
            :description="$sectionDesc ?: __('frontend.our_services_desc')"
        />
    </div>

    @if ($services && $services->isNotEmpty())
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 lg:gap-8 transition-all duration-1000 transform delay-200"
             :class="inView ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-12'">
            @foreach ($services as $service)
                @php
                    $serviceTitle = $locale === 'ar' ? $service->title_ar : ($service->title_en ?? $service->title_ar);
                    $serviceDesc  = $locale === 'ar' ? $service->description_ar : ($service->description_en ?? $service->description_ar);
                    $serviceImg   = \App\Helpers\MediaHelper::url($service, 'service_images', 'image', 'thumb');
                    $detailUrl    = route('services.show', $service->slug);
                @endphp
                <a href="{{ $detailUrl }}"
                   class="group flex flex-col h-full rounded-2xl overflow-hidden
                          bg-white dark:bg-gray-800/90
                          border border-primary-light/20 shadow-sm
                          transition-all duration-300 ease-out
                          hover:scale-[1.02] hover:-translate-y-1 hover:shadow-lg hover:shadow-[#A38B54]/10 hover:border-[#A38B54]/40 hover:bg-[#EAEAE9]/20
                          active:scale-[0.98] active:duration-150 active:bg-[#EAEAE9]/30
                          focus:outline-none
                          focus-visible:ring-2 focus-visible:ring-[#EAEAE9]
                          focus-visible:ring-offset-2 focus-visible:ring-offset-[#EAEAE9] dark:focus-visible:ring-offset-gray-900"
                   aria-label="{{ $serviceTitle }}">

                    @if ($serviceImg)
                        <div class="overflow-hidden rounded-t-2xl shrink-0">
                            <img src="{{ $serviceImg }}" alt="" aria-hidden="true" loading="lazy"
                                 class="w-full aspect-video object-cover transition-transform duration-500 group-hover:scale-105">
                        </div>
                    @endif

                    <div class="p-6 flex-1 flex flex-col justify-between">
                        <div class="space-y-4">
                            <h3 class="font-semibold text-lg leading-snug
                                       text-text-primary dark:text-gray-100 line-clamp-2">
                                {{ $serviceTitle }}
                            </h3>
                            @if ($serviceDesc)
                                <p class="font-sans text-sm sm:text-base leading-relaxed
                                          text-text-primary/80 dark:text-gray-300 line-clamp-3">
                                    {{ $serviceDesc }}
                                </p>
                            @endif
                        </div>

                        <div class="pt-4 border-t border-primary-light/10 shrink-0">
                            <span class="inline-flex items-center gap-1.5 text-sm font-semibold
                                         text-primary transition-colors duration-200
                                         group-hover:text-primary/80">
                                {{ __('frontend.read_more') }}
                                <svg class="w-4 h-4 rtl:rotate-180" fill="none" stroke="currentColor"
                                     viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </span>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    @elseif ($section && !empty($section->data))
        @php $items = $section->data; @endphp
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 lg:gap-8 transition-all duration-1000 transform delay-200"
             :class="inView ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-12'">
            @foreach ($items as $item)
                <div tabindex="0" role="button"
                     class="group flex flex-col h-full rounded-2xl overflow-hidden
                            bg-white dark:bg-gray-800/90
                            border border-primary-light/20 shadow-sm p-6
                            transition-all duration-300 ease-out
                            hover:scale-[1.02] hover:-translate-y-1 hover:shadow-lg hover:shadow-[#A38B54]/10 hover:border-[#A38B54]/40 hover:bg-[#EAEAE9]/20
                            active:scale-[0.98] active:duration-150 active:bg-[#EAEAE9]/30
                            focus:outline-none
                            focus-visible:ring-2 focus-visible:ring-[#EAEAE9]
                            focus-visible:ring-offset-2 focus-visible:ring-offset-[#EAEAE9] dark:focus-visible:ring-offset-gray-900"
                     aria-label="{{ $locale === 'ar' ? ($item['title_ar'] ?? $item['title'] ?? '') : ($item['title_en'] ?? $item['title_ar'] ?? $item['title'] ?? '') }}">
                    <div class="space-y-4">
                        @if (!empty($item['icon']))
                            <div aria-hidden="true"
                                 class="w-14 h-14 rounded-xl
                                        bg-primary-light/15 text-primary text-2xl
                                        flex items-center justify-center
                                        transition-all duration-300 ease-out
                                        group-hover:bg-[#EAEAE9]
                                        group-hover:scale-110">
                                {{ $item['icon'] }}
                            </div>
                        @endif
                        <h3 class="font-semibold text-lg leading-snug
                                   text-text-primary dark:text-gray-100">
                            {{ $locale === 'ar' ? ($item['title_ar'] ?? $item['title'] ?? '') : ($item['title_en'] ?? $item['title_ar'] ?? $item['title'] ?? '') }}
                        </h3>
                        <p class="font-sans text-sm sm:text-base leading-relaxed
                                  text-text-primary/80 dark:text-gray-300">
                            {{ $locale === 'ar' ? ($item['desc_ar'] ?? $item['desc'] ?? '') : ($item['desc_en'] ?? $item['desc_ar'] ?? $item['desc'] ?? '') }}
                        </p>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    <div class="text-center pt-10">
        <x-frontend.button :href="route('services.index')" variant="outline">
            {{ __('frontend.view_all_services') }}
        </x-frontend.button>
    </div>
</section>
