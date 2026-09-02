@php
    $locale    = app()->getLocale();
    $title     = $locale === 'ar' ? $project->title_ar : ($project->title_en ?? $project->title_ar);
    $desc      = $locale === 'ar' ? $project->description_ar : ($project->description_en ?? $project->description_ar);
    $goal      = $locale === 'ar' ? $project->goal_ar : ($project->goal_en ?? $project->goal_ar);
    $img       = \App\Helpers\MediaHelper::url($project, 'project_images', 'image', 'detail');

    $isOngoing = $project->project_status === \App\Models\Project::PROJECT_STATUS_ONGOING;
    $seo       = $project->seoMeta;
    $metaDesc  = $seo ? ($locale === 'ar' ? $seo->meta_description_ar : ($seo->meta_description_en ?? $seo->meta_description_ar)) : null;
@endphp

<x-frontend-layout :model="$project">

    <div class="max-w-4xl mx-auto space-y-8">
        <!-- Back Link -->
        <div>
            <x-frontend.button :href="route('projects.index')" variant="ghost" size="sm">
                {{ app()->getLocale() === 'ar' ? '→' : '←' }} {{ __('frontend.back_to_projects') }}
            </x-frontend.button>
        </div>

        <!-- Detail Article -->
        <article class="bg-background dark:bg-gray-800 border border-secondary/20 rounded-3xl p-6 sm:p-10 space-y-6 shadow-sm">
            @if ($img)
                <div class="overflow-hidden rounded-2xl aspect-video relative">
                    <img src="{{ $img }}" alt="{{ $title }}" loading="lazy" class="w-full h-full object-cover">
                </div>
            @endif

            <div class="space-y-4">
                <div class="flex flex-wrap items-center gap-3">
                    <x-frontend.badge :variant="$isOngoing ? 'primary' : 'accent'" size="md">
                        {{ $isOngoing ? __('frontend.ongoing') : __('frontend.completed') }}
                    </x-frontend.badge>

                    @if ($project->program)
                        <x-frontend.badge variant="secondary" size="md">
                            {{ $locale === 'ar' ? $project->program->title_ar : ($project->program->title_en ?? $project->program->title_ar) }}
                        </x-frontend.badge>
                    @endif
                </div>

                <h1 class="text-2xl sm:text-3xl font-bold text-text-primary dark:text-background">
                    {{ $title }}
                </h1>

                <!-- Dates Bar -->
                @if ($project->start_date || $project->end_date)
                    <div class="flex flex-wrap items-center gap-6 py-3 px-4 rounded-xl bg-background dark:bg-gray-900/60 text-xs font-semibold text-text-primary/70 dark:text-gray-300">
                        @if ($project->start_date)
                            <div>
                                <span class="text-primary dark:text-secondary">{{ __('frontend.start_date') }}</span>
                                {{ $project->start_date->translatedFormat('d M Y') }}
                            </div>
                        @endif
                        @if ($project->end_date)
                            <div>
                                <span class="text-primary dark:text-secondary">{{ __('frontend.end_date') }}</span>
                                {{ $project->end_date->translatedFormat('d M Y') }}
                            </div>
                        @endif

                    </div>
                @endif

                @if ($goal)
                    <div class="p-4 rounded-2xl bg-secondary/30 border border-secondary/30 space-y-1">
                        <h3 class="text-sm font-bold text-primary dark:text-secondary">{{ __('frontend.project_goal') }}</h3>
                        <p class="text-sm text-text-primary/80 dark:text-gray-200">{{ $goal }}</p>
                    </div>
                @endif

                @if ($desc)
                    <div class="text-base text-text-primary/80 dark:text-gray-300 leading-relaxed space-y-4 whitespace-pre-line pt-2">
                        {{ $desc }}
                    </div>
                @endif
            </div>

            <x-frontend.external-link-button :model="$project" collection="project_images" />
        </article>
    </div>

</x-frontend-layout>
