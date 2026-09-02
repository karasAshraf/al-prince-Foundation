<x-frontend-layout title="{{ __('frontend.services') }}">

    <div x-data="{
        open: false,
        service: { title: '', desc: '', img: '', icon: '', externalLink: '', shouldShowLink: false },
        openModal(data) {
            this.service = data;
            this.open = true;
            document.body.style.overflow = 'hidden';
        },
        closeModal() {
            this.open = false;
            document.body.style.overflow = '';
        }
    }">

        <!-- Page Header -->
        @php
            $hasServicesHero = \App\Models\HeroSlide::active()->where('placement', 'services')->exists();
        @endphp

        @if(!$hasServicesHero)
        <div class="text-center mb-12">
            <x-frontend.badge variant="secondary">{{ __('frontend.our_services') }}</x-frontend.badge>
            <h1 class="text-3xl sm:text-4xl font-bold text-text-primary dark:text-background mt-3 leading-tight">
                {{ __('frontend.services_and_programs') }}
            </h1>
            
        </div>
        @endif

        @if ($services->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-8">
                @foreach ($services as $service)
                    @php
                        $locale    = app()->getLocale();
                        $title     = $locale === 'ar' ? $service->title_ar : ($service->title_en ?? $service->title_ar);
                        $desc      = $locale === 'ar' ? $service->description_ar : ($service->description_en ?? $service->description_ar);
                        $img       = \App\Helpers\MediaHelper::url($service, 'service_images', 'image', 'card');
                    @endphp

                    <div class="group flex flex-col text-start h-full rounded-2xl overflow-hidden
                                   bg-background dark:bg-gray-800/90
                                   border border-secondary/20
                                   shadow-sm
                                   transition-all duration-300 ease-out
                                   hover:scale-[1.02] hover:-translate-y-1 hover:shadow-lg hover:shadow-[#A38B54]/10 hover:border-[#A38B54]/40 hover:bg-secondary/20">

                        {{-- Hero image --}}
                        @if ($img)
                            <div class="overflow-hidden rounded-t-2xl shrink-0 w-full">
                                <img src="{{ $img }}" alt="" aria-hidden="true" width="400" height="300" loading="lazy"
                                     class="w-full aspect-video object-cover transition-transform duration-500 group-hover:scale-105">
                            </div>
                        @endif

                        {{-- Card body --}}
                        <div class="p-6 flex-1 flex flex-col justify-between w-full">
                            <div class="space-y-4">
                                @if ($service->icon)
                                    <div aria-hidden="true"
                                         class="w-14 h-14 rounded-xl
                                                bg-secondary/15 text-primary text-2xl
                                                flex items-center justify-center
                                                transition-all duration-300 ease-out
                                                group-hover:bg-secondary
                                                group-hover:scale-110">
                                        {{ $service->icon }}
                                    </div>
                                @endif

                                <h3 class="font-semibold text-lg leading-snug
                                           text-text-primary dark:text-background
                                           line-clamp-2">
                                    {{ $title }}
                                </h3>

                                @if ($desc)
                                    <p class="font-sans text-sm sm:text-base leading-relaxed
                                              text-text-primary/80 dark:text-gray-300
                                              line-clamp-3">
                                        {{ $desc }}
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-8">
                <x-frontend.pagination :paginator="$services" />
            </div>
        @else
            <x-frontend.empty-state
                :title="__('frontend.no_services_available')"
                :description="__('frontend.services_coming_soon')"
            />
        @endif

        <!-- Service Details Modal -->
        <div x-show="open"
             class="fixed inset-0 z-50 overflow-y-auto"
             style="display: none;"
             x-cloak>
            <!-- Backdrop -->
            <div class="fixed inset-0 bg-black/50 backdrop-blur-sm transition-opacity"
                 x-show="open"
                 x-transition.opacity
                 @click="closeModal()"></div>

            <!-- Modal Wrapper -->
            <div class="flex min-h-screen items-center justify-center p-4 sm:p-6 md:p-10">
                <div class="relative bg-background dark:bg-gray-800 border border-secondary/20 rounded-3xl w-full max-w-4xl shadow-2xl overflow-hidden transition-all duration-300 transform"
                     x-show="open"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-4 scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                     x-transition:leave-end="opacity-0 translate-y-4 scale-95"
                     @click.outside="closeModal()">
                    
                    <!-- Close Button -->
                    <button @click="closeModal()"
                            class="absolute top-4 end-4 z-10 p-2 rounded-full bg-black/10 hover:bg-black/20 text-text-primary dark:text-background transition-colors"
                            aria-label="{{ app()->getLocale() === 'ar' ? 'إغلاق' : 'Close' }}">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>

                    <!-- Modal Content -->
                    <div class="p-6 sm:p-10 space-y-6">
                        <template x-if="service.img">
                            <div class="overflow-hidden rounded-2xl aspect-video">
                                <img :src="service.img" :alt="service.title" class="w-full h-full object-cover">
                            </div>
                        </template>

                        <div class="space-y-4">
                            <div class="flex items-center gap-3">
                                <template x-if="service.icon">
                                    <span class="text-3xl" x-text="service.icon"></span>
                                </template>
                                <h2 class="text-2xl sm:text-3xl font-bold text-text-primary dark:text-background" x-text="service.title"></h2>
                            </div>

                            <p class="text-base text-text-primary/80 dark:text-gray-300 leading-relaxed whitespace-pre-line" x-text="service.desc"></p>
                        </div>

                        <!-- External Link -->
                        <template x-if="service.shouldShowLink && service.externalLink">
                            <div class="pt-6 border-t border-secondary/20">
                                <a :href="service.externalLink" target="_blank" rel="noopener noreferrer"
                                   class="inline-flex items-center gap-2.5 px-6 py-3 text-sm font-bold rounded-xl bg-primary text-background hover:bg-[#8A734A] hover:shadow-md transition-all duration-300 transform hover:-translate-y-0.5">
                                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.6 9h16.8M3.6 15h16.8" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.5 3a17 17 0 000 18M12.5 3a17 17 0 010 18" />
                                    </svg>
                                    <span>{{ app()->getLocale() === 'ar' ? 'زيارة الموقع الإلكتروني' : 'Visit Website' }}</span>
                                    <svg class="w-4 h-4 shrink-0 opacity-80 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                    </svg>
                                </a>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </div>

    </div>

</x-frontend-layout>
