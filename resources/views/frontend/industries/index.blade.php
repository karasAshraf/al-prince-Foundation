<x-frontend-layout title="{{ __('frontend.industries') }}">

    <div x-data="{
        open: false,
        industry: { title: '', desc: '', img: '' },
        openModal(data) {
            this.industry = data;
            this.open = true;
            document.body.style.overflow = 'hidden';
        },
        closeModal() {
            this.open = false;
            document.body.style.overflow = '';
        }
    }">

        <!-- Page Header -->
        <div class="text-center mb-12">
            <x-frontend.badge variant="secondary">{{ __('frontend.our_industries') }}</x-frontend.badge>
            <h1 class="text-3xl sm:text-4xl font-bold text-text-primary dark:text-background mt-3 leading-tight">
                {{ __('frontend.industries_title') }}
            </h1>
          
        </div>

        @if ($industries->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
                @foreach ($industries as $industry)
                    @php
                        $locale    = app()->getLocale();
                        $title     = $locale === 'ar' ? $industry->title_ar : ($industry->title_en ?? $industry->title_ar);
                        $desc      = $locale === 'ar' ? $industry->description_ar : ($industry->description_en ?? $industry->description_ar);
                        $img       = \App\Helpers\MediaHelper::url($industry, 'industry_images', 'image', 'card');
                        $detailImg = \App\Helpers\MediaHelper::url($industry, 'industry_images', 'image', 'detail');
                    @endphp

                    <button type="button"
                            @click="openModal({
                                title: '{{ e($title) }}',
                                desc: `{{ e($desc) }}`,
                                img: '{{ $detailImg }}'
                            })"
                            class="group relative bg-background dark:bg-gray-800 border border-secondary/20 rounded-2xl flex flex-col items-start text-start hover:shadow-lg hover:border-primary transition-all duration-300 hover:-translate-y-1 w-full h-full focus:outline-none focus:ring-2 focus:ring-primary overflow-hidden">
                        
                        @if($img)
                            <div class="w-full aspect-[16/10] overflow-hidden bg-background dark:bg-gray-700 shrink-0 border-b border-secondary/10">
                                <img src="{{ $img }}" alt="{{ $title }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" />
                            </div>
                        @else
                            <div class="w-full aspect-[16/10] bg-background dark:bg-gray-700 flex items-center justify-center shrink-0 border-b border-secondary/10">
                                <svg class="w-12 h-12 text-primary/40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                            </div>
                        @endif

                        <div class="p-6 flex-1 flex flex-col w-full">
                            <h3 class="text-lg font-bold text-text-primary dark:text-background mb-3 group-hover:text-primary transition-colors leading-snug">{{ $title }}</h3>
                            
                            @if($desc)
                                <p class="text-sm text-text-primary dark:text-gray-400 line-clamp-2 mb-4 leading-relaxed">{{ $desc }}</p>
                            @endif

                            <div class="mt-auto pt-4 flex items-center justify-between text-primary font-semibold text-sm w-full border-t border-background dark:border-gray-700/50">
                                <span>{{ __('frontend.industry_details') ?? 'Explore' }}</span>
                                <div class="w-6 h-6 rounded-full bg-primary/10 flex items-center justify-center group-hover:bg-primary group-hover:text-background transition-all duration-300">
                                    <svg class="w-3.5 h-3.5 rtl:-scale-x-100 transform group-hover:translate-x-0.5 rtl:group-hover:-translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                </div>
                            </div>
                        </div>
                    </button>
                @endforeach
            </div>

            <div class="mt-10">
                <x-frontend.pagination :paginator="$industries" />
            </div>
        @else
            <x-frontend.empty-state
                :title="__('frontend.no_industries_available')"
                :description="__('frontend.industries_coming_soon')"
            />
        @endif

        <!-- Industry Details Modal -->
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
                        <template x-if="industry.img">
                            <div class="overflow-hidden rounded-2xl aspect-video">
                                <img :src="industry.img" :alt="industry.title" class="w-full h-full object-cover">
                            </div>
                        </template>

                        <div class="space-y-4">
                            <h2 class="text-2xl sm:text-3xl font-bold text-text-primary dark:text-background" x-text="industry.title"></h2>
                            <p class="text-base text-text-primary/80 dark:text-gray-300 leading-relaxed whitespace-pre-line pt-6 border-t border-secondary/20" x-text="industry.desc"></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

</x-frontend-layout>
