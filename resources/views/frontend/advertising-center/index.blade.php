<x-frontend-layout title="{{ __('frontend.advertising_center_title') }}">

    <!-- Page Header -->
    <div class="text-center mb-16 max-w-2xl mx-auto space-y-4">
        <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-secondary-light/40 text-primary text-xs font-semibold tracking-widest uppercase">
            {{ __('frontend.advertising_center') }}
        </span>
        
        <h1 class="text-4xl sm:text-5xl font-extrabold leading-tight tracking-tight mt-3">
            @if(app()->getLocale() === 'ar')
                <span class="bg-gradient-to-r from-primary to-primary-light bg-clip-text text-transparent">المركز</span>
                <span class="text-text-primary dark:text-surface"> الإعلاني</span>
            @else
                <span class="bg-gradient-to-r from-primary to-primary-light bg-clip-text text-transparent">Media</span>
                <span class="text-text-primary dark:text-surface"> Hub</span>
            @endif
        </h1>
        
        <p class="text-lg text-text-primary/70 dark:text-gray-400 leading-relaxed">
            {{ __('frontend.advertising_center_desc') }}
        </p>

        {{-- Decorative underline --}}
        <div class="flex items-center justify-center gap-2 pt-1">
            <span class="h-px w-12 bg-primary-light/40 rounded-full"></span>
            <span class="w-2 h-2 rounded-full bg-primary"></span>
            <span class="h-px w-24 bg-primary/60 rounded-full"></span>
            <span class="w-2 h-2 rounded-full bg-primary"></span>
            <span class="h-px w-12 bg-primary-light/40 rounded-full"></span>
        </div>
    </div>

    <!-- Navigation Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-6xl mx-auto"
         x-data="{ inView: false }"
         x-intersect.once="inView = true">

        <!-- News Card -->
        <div :class="inView ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-6'"
             class="transition-all duration-700 ease-out delay-0">
            <x-frontend.navigation-card
                :href="route('news.index')"
                icon="newspaper"
                :title="__('frontend.news')"
                :description="__('frontend.news_desc')"
                :btnText="__('frontend.go_to_news')"
                number="01"
            />
        </div>

        <!-- Events Card -->
        <div :class="inView ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-6'"
             class="transition-all duration-700 ease-out delay-100">
            <x-frontend.navigation-card
                :href="route('events.index')"
                icon="calendar"
                :title="__('frontend.events')"
                :description="__('frontend.events_desc')"
                :btnText="__('frontend.go_to_events')"
                number="02"
            />
        </div>

        <!-- Media Library Card -->
        <div :class="inView ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-6'"
             class="transition-all duration-700 ease-out delay-200">
            <x-frontend.navigation-card
                :href="route('media-library.index')"
                icon="folder-open"
                :title="__('frontend.media_library')"
                :description="__('frontend.media_library_desc')"
                :btnText="__('frontend.go_to_media_library')"
                number="03"
            />
        </div>

    </div>

</x-frontend-layout>
