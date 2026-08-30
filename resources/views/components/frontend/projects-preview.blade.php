@props(['section'])

@php
    $locale = app()->getLocale();
    $title  = $locale === 'ar' ? $section->title_ar : ($section->title_en ?? $section->title_ar);
    $desc   = $locale === 'ar' ? $section->description_ar : ($section->description_en ?? $section->description_ar);
@endphp

<section {{ $attributes->merge(['class' => 'py-12 md:py-20 bg-secondary-light/10 dark:bg-gray-900/40 rounded-3xl p-6 sm:p-10 my-8']) }}
         x-data="{ inView: false }"
         x-intersect.once="inView = true">
    <div class="transition-all duration-1000 transform"
         :class="inView ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-8'">
        <x-frontend.section-title
            badge="{{ __('frontend.our_projects') }}"
            :title="$title ?? __('frontend.projects_under_execution')"
            :description="$desc ?? __('frontend.projects_preview_desc')"
        />

        <div class="text-center pt-4 transition-all duration-1000 transform delay-200"
             :class="inView ? 'opacity-100 scale-100' : 'opacity-0 scale-95'">
            <x-frontend.button :href="route('projects.index')" variant="primary">
                {{ __('frontend.view_all_projects') }}
            </x-frontend.button>
        </div>
    </div>
</section>
