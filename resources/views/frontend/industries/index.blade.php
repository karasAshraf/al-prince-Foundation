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
            <h1 class="text-3xl sm:text-4xl font-bold text-text-primary dark:text-surface mt-3 leading-tight">
                {{ __('frontend.industries_title') }}
            </h1>
            <p class="mt-4 text-text-primary/70 dark:text-gray-400 max-w-xl mx-auto">
                {{ __('frontend.industries_page_desc') }}
            </p>
        </div>

        @if ($industries->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-8">
                @foreach ($industries as $industry)
                    @php
                        $locale    = app()->getLocale();
                        $title     = $locale === 'ar' ? $industry->title_ar : ($industry->title_en ?? $industry->title_ar);
                        $desc      = $locale === 'ar' ? $industry->description_ar : ($industry->description_en ?? $industry->description_ar);
                        $img       = \App\Helpers\MediaHelper::url($industry, 'industry_images', 'image', 'card');
                        $detailImg = \App\Helpers\MediaHelper::url($industry, 'industry_images', 'image', 'detail');
                    @endphp

                    <x-frontend.card :hoverable="true" :padding="'none'" class="flex flex-col justify-between h-full group">
                        @if ($img)
                            <div class="overflow-hidden rounded-t-2xl shrink-0">
                                <button type="button"
                                        @click="openModal({
                                            title: '{{ e($title) }}',
                                            desc: `{{ e($desc) }}`,
                                            img: '{{ $detailImg }}'
                                        })"
                                        class="block w-full focus:outline-none">
                                    <img src="{{ $img }}" alt="{{ $title }}" width="400" height="300" loading="lazy"
                                         class="w-full aspect-video object-cover transition-transform duration-500 group-hover:scale-105">
                                </button>
                            </div>
                        @endif

                        <div class="p-6 flex-1 flex flex-col justify-between">
                            <div class="space-y-4">
                                <h3 class="text-xl font-bold text-text-primary dark:text-gray-100 line-clamp-2 text-start">
                                    <button type="button"
                                            @click="openModal({
                                                title: '{{ e($title) }}',
                                                desc: `{{ e($desc) }}`,
                                                img: '{{ $detailImg }}'
                                            })"
                                            class="hover:text-primary transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary rounded-md text-start font-bold">
                                        {{ $title }}
                                    </button>
                                </h3>

                                @if ($desc)
                                    <p class="text-sm text-text-primary/75 dark:text-gray-300 leading-relaxed line-clamp-3 text-start">
                                        {{ $desc }}
                                    </p>
                                @endif
                            </div>
                        </div>

                        <div class="px-6 pb-6 pt-4 border-t border-primary-light/10">
                            <x-frontend.button type="button"
                                               @click="openModal({
                                                   title: '{{ e($title) }}',
                                                   desc: `{{ e($desc) }}`,
                                                   img: '{{ $detailImg }}'
                                               })"
                                               variant="outline"
                                               size="sm"
                                               class="w-full justify-center">
                                {{ __('frontend.industry_details') }}
                            </x-frontend.button>
                        </div>
                    </x-frontend.card>
                @endforeach
            </div>

            <div class="mt-8">
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
                <div class="relative bg-white dark:bg-gray-800 border border-primary-light/20 rounded-3xl w-full max-w-4xl shadow-2xl overflow-hidden transition-all duration-300 transform"
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
                            class="absolute top-4 end-4 z-10 p-2 rounded-full bg-black/10 hover:bg-black/20 text-text-primary dark:text-white transition-colors"
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
                            <h2 class="text-2xl sm:text-3xl font-bold text-text-primary dark:text-gray-100" x-text="industry.title"></h2>
                            <p class="text-base text-text-primary/80 dark:text-gray-300 leading-relaxed whitespace-pre-line pt-6 border-t border-primary-light/20" x-text="industry.desc"></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

</x-frontend-layout>
