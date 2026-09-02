<x-frontend-layout title="{{ __('frontend.content_management_title') }}">

    <!-- Page Header -->
    <div class="text-center mb-16 max-w-2xl mx-auto space-y-4">
        <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-secondary/40 text-primary text-xs font-semibold tracking-widest uppercase">
            {{ __('frontend.content_management') }}
        </span>
        
           <h1 class="text-4xl sm:text-5xl font-extrabold leading-tight tracking-tight mt-3">
        @if(app()->getLocale() === 'ar')
            <span class="bg-gradient-to-r from-primary to-primary-light bg-clip-text text-transparent">الحلول</span>
            <span class="text-text-primary dark:text-background"> والخدمات</span>
        @else
            <span class="bg-gradient-to-r from-primary to-primary-light bg-clip-text text-transparent">Solutions</span>
            <span class="text-text-primary dark:text-background"> and Services</span>
        @endif
    </h1>
        
        <p class="text-lg text-text-primary/70 dark:text-gray-400 leading-relaxed">
            {{ __('frontend.content_management_description') }}
        </p>

        {{-- Decorative underline --}}
        <div class="flex items-center justify-center gap-2 pt-1">
            <span class="h-px w-12 bg-secondary/40 rounded-full"></span>
            <span class="w-2 h-2 rounded-full bg-primary"></span>
            <span class="h-px w-24 bg-primary/60 rounded-full"></span>
            <span class="w-2 h-2 rounded-full bg-primary"></span>
            <span class="h-px w-12 bg-secondary/40 rounded-full"></span>
        </div>
    </div>

    <!-- Navigation Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 lg:gap-8 max-w-7xl mx-auto"
         x-data="{ inView: false }"
         x-intersect.once="inView = true">

        <!-- Services Card -->
        <div :class="inView ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-6'"
             class="transition-all duration-700 ease-out delay-0">
            <x-frontend.navigation-card
                :href="route('services.index')"
                icon="briefcase"
                :title="__('frontend.services')"
                :description="__('frontend.services_description')"
                :btnText="__('frontend.go_to_services')"
                number="01"
            />
        </div>

        <!-- Activities Card -->
        <div :class="inView ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-6'"
             class="transition-all duration-700 ease-out delay-100">
            <x-frontend.navigation-card
                :href="route('activities.index')"
                icon="activity"
                :title="__('frontend.activities')"
                :description="__('frontend.activities_description')"
                :btnText="__('frontend.go_to_activities')"
                number="02"
            />
        </div>

        <!-- Industries Card -->
        <div :class="inView ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-6'"
             class="transition-all duration-700 ease-out delay-200">
            <x-frontend.navigation-card
                :href="route('industries.index')"
                icon="building-2"
                :title="__('frontend.industries')"
                :description="__('frontend.industries_description')"
                :btnText="__('frontend.go_to_industries')"
                number="03"
            />
        </div>

        <!-- Solutions Card -->
        <div :class="inView ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-6'"
             class="transition-all duration-700 ease-out delay-300">
            <x-frontend.navigation-card
                :href="route('solutions.index')"
                icon="lightbulb"
                :title="__('frontend.solutions')"
                :description="__('frontend.solutions_description')"
                :btnText="__('frontend.go_to_solutions')"
                number="04"
            />
        </div>

    </div>

</x-frontend-layout>
