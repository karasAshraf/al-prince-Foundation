<x-frontend-layout title="{{ __('frontend.organizational_structure') }}">

    @php
        $isRtl = app()->getLocale() === 'ar';
        $imgUrl = null;
        $title = '';
        $desc = '';

        if ($structure) {
            $title = $isRtl ? $structure->title_ar : ($structure->title_en ?? $structure->title_ar);
            $desc = $isRtl ? $structure->description_ar : ($structure->description_en ?? $structure->description_ar);
            
            if (!$isRtl) {
                $imgUrl = \App\Helpers\MediaHelper::url($structure, 'organizational_structure_en', 'image_en')
                    ?: \App\Helpers\MediaHelper::url($structure, 'organizational_structure_ar', 'image_ar');
            } else {
                $imgUrl = \App\Helpers\MediaHelper::url($structure, 'organizational_structure_ar', 'image_ar');
            }
        }
    @endphp

    {{-- Breadcrumbs Navigation --}}
    <nav class="flex items-center gap-2 text-sm text-text-primary/60 dark:text-gray-400 mt-8 mb-6 justify-center" aria-label="Breadcrumb">
        <a href="{{ route('home') }}" class="hover:text-primary transition-colors duration-200">{{ __('frontend.home') }}</a>
        <span class="text-text-primary/30">/</span>
        <a href="{{ route('about.index') }}" class="hover:text-primary transition-colors duration-200">{{ __('frontend.about_foundation') }}</a>
        <span class="text-text-primary/30">/</span>
        <span class="text-primary font-bold">{{ __('frontend.organizational_structure') }}</span>
    </nav>

    {{-- ── Page Header ──────────────────────────────────────────────────── --}}
    <div class="relative text-center pb-6 mb-8 sm:mb-12">
        <div aria-hidden="true" class="absolute inset-0 -z-10 flex items-center justify-center pointer-events-none">
            <div class="w-72 h-72 sm:w-96 sm:h-96 rounded-full bg-gradient-to-br from-secondary/20 via-primary/10 to-transparent blur-3xl opacity-60"></div>
        </div>

        <span class="text-xs sm:text-sm font-bold uppercase tracking-wider text-primary mb-3 block">
            {{ __('frontend.about_foundation') }}
        </span>

        <h1 class="text-3xl sm:text-4xl lg:text-5xl font-black text-text-primary dark:text-background leading-tight tracking-tight mb-4">
            {{ $title ?: __('frontend.organizational_structure') }}
        </h1>

        <div class="flex items-center justify-center gap-1.5 mt-4 mb-6" aria-hidden="true">
            <div class="w-3 h-1.5 rounded-full bg-primary"></div>
            <div class="w-1.5 h-1.5 rounded-full bg-secondary"></div>
            <div class="w-6 h-1 rounded-full bg-primary"></div>
        </div>

        @if ($desc)
            <p class="mt-4 text-base sm:text-lg text-text-primary dark:text-gray-300 max-w-2xl mx-auto leading-relaxed">
                {{ $desc }}
            </p>
        @endif
    </div>

    {{-- ── Main Image / Lightbox Section ─────────────────────────────────── --}}
    <x-frontend.section index="0" align="center" class="pt-0">
        @if ($imgUrl)
            <div x-data="{ open: false }" class="relative w-full max-w-5xl mx-auto" @keydown.escape.window="open = false">
                
                {{-- Contained Interactive Card / Frame --}}
                <div class="relative group rounded-3xl border-2 border-primary/20 bg-background dark:bg-gray-800 shadow-xl hover:shadow-2xl hover:border-primary/40 transition-all duration-300 overflow-hidden p-3 sm:p-6">
                    
                    {{-- Hint overlay --}}
                    <div class="absolute inset-0 bg-text-primary/5 group-hover:bg-text-primary/0 transition-colors duration-300 pointer-events-none z-10"></div>
                    
                    {{-- Scroll wrapper for mobile with a pinch indicator --}}
                    <div class="overflow-x-auto overflow-y-hidden custom-scrollbar w-full flex justify-center cursor-zoom-in" @click="open = true">
                        <img 
                            src="{{ $imgUrl }}" 
                            alt="{{ $title ?: __('frontend.organizational_structure') }}" 
                            class="max-w-none md:max-w-full h-auto min-h-[300px] md:h-auto max-h-[80vh] object-contain transition-transform duration-500 group-hover:scale-[1.01]"
                        />
                    </div>

                    {{-- Bottom interactive bar / hint --}}
                    <div class="mt-4 flex flex-wrap items-center justify-between gap-4 text-xs font-semibold text-text-primary dark:text-gray-400 border-t border-primary/10 pt-4">
                        <span class="flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                            </svg>
                            <span>{{ __('frontend.pinchtozoom') }}</span>
                        </span>

                        <button @click="open = true" type="button" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-primary text-background hover:bg-primary active:scale-[0.98] transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v6m4-3H6"/>
                            </svg>
                            <span>{{ __('frontend.viewfullsecreen') }}</span>
                        </button>
                    </div>
                </div>

                {{-- Lightbox Modal --}}
                <template x-teleport="body">
                    <div 
                        x-show="open" 
                        x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0"
                        x-transition:enter-end="opacity-100"
                        x-transition:leave="transition ease-in duration-200"
                        x-transition:leave-start="opacity-100"
                        x-transition:leave-end="opacity-0"
                        class="fixed inset-0 z-[100] flex items-center justify-center bg-text-primary/95 backdrop-blur-md p-4 sm:p-8"
                        style="display: none;"
                        role="dialog"
                        aria-modal="true"
                    >
                        {{-- Close button --}}
                        <button 
                            @click="open = false" 
                            type="button" 
                            class="absolute top-4 end-4 z-[110] p-3 rounded-full bg-background/10 text-background hover:bg-background/20 transition-all focus:outline-none focus:ring-2 focus:ring-primary"
                            aria-label="{{ __('frontend.close') ?? 'Close' }}"
                        >
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>

                        {{-- Full-screen diagram container --}}
                        <div 
                            class="relative w-full h-full flex items-center justify-center" 
                            @click.self="open = false"
                        >
                            <img 
                                src="{{ $imgUrl }}" 
                                alt="{{ $title ?: __('frontend.organizational_structure') }}" 
                                class="max-w-full max-h-full object-contain rounded-xl select-none"
                            />
                        </div>
                    </div>
                </template>

            </div>
        @else
            {{-- Empty state --}}
            <x-frontend.empty-state
                :title="__('frontend.organizational_structure')"
                :description="__('frontend.content_coming_soon') ?: 'سيتم إضافة الهيكل التنظيمي قريباً.'"
            >
                <x-slot:action>
                    <x-frontend.button :href="route('about.index')" variant="outline" size="sm">
                        {{ $isRtl ? '→' : '←' }} {{ __('frontend.back_to_about') }}
                    </x-frontend.button>
                </x-slot:action>
            </x-frontend.empty-state>
        @endif
    </x-frontend.section>

    {{-- Back navigation --}}
    <div class="text-center mt-12 mb-16">
        <x-frontend.button :href="route('about.index')" variant="ghost" size="md">
            @if ($isRtl)
                <svg class="w-4 h-4 inline-block me-1 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
            @else
                <svg class="w-4 h-4 inline-block me-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
            @endif
            {{ __('frontend.back_to_about') }}
        </x-frontend.button>
    </div>

</x-frontend-layout>
