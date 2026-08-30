@props([
    'section',
    'index' => 3,
])

@php
    $locale = app()->getLocale();
    $title  = $locale === 'ar' ? $section->title_ar : ($section->title_en ?? $section->title_ar);
    $desc   = $locale === 'ar' ? $section->description_ar : ($section->description_en ?? $section->description_ar);
@endphp

<x-frontend.section 
    badge="{{ __('frontend.our_projects') }}"
    :title="$title ?? __('frontend.projects_under_execution')"
    :description="$desc ?? __('frontend.projects_preview_desc')"
    :index="$index"
    align="center"
    x-data="{ inView: false }"
    x-intersect.once="inView = true"
>
    <div class="text-center pt-4 transition-all duration-1000 transform delay-200"
         :class="inView ? 'opacity-100 scale-100' : 'opacity-0 scale-95'">
        <x-frontend.button :href="route('projects.index')" variant="primary">
            {{ __('frontend.view_all_projects') }}
        </x-frontend.button>
    </div>
</x-frontend.section>
