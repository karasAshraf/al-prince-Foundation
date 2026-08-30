<x-frontend-layout title="{{ __('frontend.page_not_found_title') }}">
    <div class="min-h-[60vh] flex items-center justify-center py-16">
        <div class="text-center space-y-6 max-w-lg mx-auto">
            <div class="w-24 h-24 rounded-3xl bg-primary/10 text-primary flex items-center justify-center text-4xl font-bold mx-auto shadow-sm">
                404
            </div>
            <h1 class="text-3xl font-bold text-text-primary dark:text-gray-100">
                {{ __('frontend.page_not_found_heading') }}
            </h1>
            <p class="text-text-primary/70 dark:text-gray-400 text-sm leading-relaxed">
                {{ __('frontend.page_not_found_desc') }}
            </p>
            <div class="pt-4">
                <x-frontend.button :href="route('home')" variant="primary" size="md">
                    {{ app()->getLocale() === 'ar' ? '→' : '←' }} {{ __('frontend.back_to_home') }}
                </x-frontend.button>
            </div>
        </div>
    </div>
</x-frontend-layout>
