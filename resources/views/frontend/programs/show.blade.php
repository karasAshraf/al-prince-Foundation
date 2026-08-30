@php
    $locale = app()->getLocale();
    $title  = $locale === 'ar' ? $program->title_ar : ($program->title_en ?? $program->title_ar);
    $desc   = $locale === 'ar' ? $program->description_ar : ($program->description_en ?? $program->description_ar);
    $summary = $locale === 'ar'
        ? ($program->summary_ar ?: $program->summary_en)
        : ($program->summary_en ?: $program->summary_ar);
    $img    = \App\Helpers\MediaHelper::url($program, 'program_images', 'image', 'detail');

    $seo    = $program->seoMeta;
    $metaDesc = $seo ? ($locale === 'ar' ? $seo->meta_description_ar : ($seo->meta_description_en ?? $seo->meta_description_ar)) : null;
@endphp

<x-frontend-layout :model="$program">

    <div class="max-w-4xl mx-auto space-y-10">
        <!-- Back Button -->
        <div>
            <x-frontend.button :href="route('programs.index')" variant="ghost" size="sm">
                {{ app()->getLocale() === 'ar' ? '→' : '←' }} {{ __('frontend.back_to_programs') }}
            </x-frontend.button>
        </div>

        <!-- Program Main Card -->
        <article class="bg-white dark:bg-gray-800 border border-primary-light/20 rounded-3xl p-6 sm:p-10 space-y-6 shadow-sm">
            @if ($img)
                <div class="overflow-hidden rounded-2xl aspect-video">
                    <img src="{{ $img }}" alt="{{ $title }}" loading="lazy" class="w-full h-full object-cover">
                </div>
            @endif

            <div class="space-y-4">
                <x-frontend.badge variant="secondary">{{ __('frontend.development_program') }}</x-frontend.badge>
                <h1 class="text-2xl sm:text-3xl font-bold text-text-primary dark:text-gray-100">
                    {{ $title }}
                </h1>

                @if ($summary)
                    <div class="text-lg font-semibold text-text-primary/90 dark:text-gray-200 border-s-4 border-primary ps-4 leading-relaxed whitespace-pre-line">
                        {{ $summary }}
                    </div>
                @endif

                @if ($desc)
                    <div class="text-base text-text-primary/80 dark:text-gray-300 leading-relaxed space-y-4 whitespace-pre-line pt-2">
                        {{ $desc }}
                    </div>
                @endif
            </div>

            <x-frontend.external-link-button :model="$program" collection="program_images" />
        </article>

        <!-- Program Projects Section -->
        <div class="space-y-6">
            <h2 class="text-2xl font-bold text-text-primary dark:text-gray-100">
                {{ __('frontend.program_projects') }} ({{ $projects->count() }})
            </h2>

            @if ($projects->isNotEmpty())
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @foreach ($projects as $project)
                        @php
                            $projTitle = $locale === 'ar' ? $project->title_ar : ($project->title_en ?? $project->title_ar);
                            $projDesc  = $locale === 'ar' ? $project->description_ar : ($project->description_en ?? $project->description_ar);
                            $projImg   = \App\Helpers\MediaHelper::url($project, 'project_images', 'image', 'card');

                        @endphp
                        <x-frontend.card :hoverable="true" class="flex flex-col justify-between h-full">
                            <div class="space-y-3">
                                @if ($projImg)
                                    <div class="overflow-hidden rounded-xl h-40 -mx-6 -mt-6 mb-3">
                                        <img src="{{ $projImg }}" alt="{{ $projTitle }}" loading="lazy" class="w-full h-full object-cover">
                                    </div>
                                @endif
                                <h3 class="text-lg font-bold text-text-primary dark:text-gray-100">
                                    {{ $projTitle }}
                                </h3>
                                @if ($projDesc)
                                    <p class="text-sm text-text-primary/75 dark:text-gray-300 line-clamp-3">
                                        {{ $projDesc }}
                                    </p>
                                @endif
                            </div>
                            <div class="pt-4">
                                <x-frontend.button :href="route('projects.show', $project->slug)" variant="outline" size="sm" class="w-full justify-center">
                                    {{ __('frontend.project_details') }}
                                </x-frontend.button>
                            </div>
                        </x-frontend.card>
                    @endforeach
                </div>
            @else
                <x-frontend.empty-state
                    :title="__('frontend.no_projects_under_program')"
                />
            @endif
        </div>
    </div>

</x-frontend-layout>
