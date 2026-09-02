<x-frontend-layout title="{{ __('frontend.projects') }}">

    @php $locale = app()->getLocale(); @endphp


    {{-- ── Creative Layout and Storytelling Grid ────────────────────────── --}}
    <div class="py-8 md:py-12">
        @if ($projects->count() > 0)
            @php
                $featuredProject = $projects->first();
                $otherProjects = $projects->skip(1);
            @endphp

            {{-- 1. Featured Project Card (Editorial Focus) --}}
            <section class="mb-16" aria-label="{{ $locale === 'ar' ? 'المشروع البارز' : 'Featured Project' }}">
                <div class="text-start mb-6">
                    <h2 class="text-2xl font-black text-[#3D342A] dark:text-background flex items-center gap-2">
                        <span class="w-1.5 h-6 bg-primary rounded-full"></span>
                        <span>{{ $locale === 'ar' ? 'المشروع البارز' : 'Featured Project' }}</span>
                    </h2>
                </div>
                
                @php
                    $fTitle     = $locale === 'ar' ? $featuredProject->title_ar : ($featuredProject->title_en ?? $featuredProject->title_ar);
                    $fDesc      = $locale === 'ar' ? $featuredProject->description_ar : ($featuredProject->description_en ?? $featuredProject->description_ar);
                    $fImg       = \App\Helpers\MediaHelper::url($featuredProject, 'project_images', 'image', 'card');
                    $fIsOngoing = $featuredProject->project_status === \App\Models\Project::PROJECT_STATUS_ONGOING;
                    $fDetailUrl = route('projects.show', $featuredProject->slug);
                    $fProgram   = $featuredProject->program ? ($locale === 'ar' ? $featuredProject->program->title_ar : ($featuredProject->program->title_en ?? $featuredProject->program->title_ar)) : null;
                @endphp

                <article class="relative overflow-hidden bg-background dark:bg-gray-800 rounded-3xl border border-[#A38B54]/10 dark:border-gray-700/60 shadow-sm hover:shadow-xl transition-all duration-300 group">
                    <div class="grid grid-cols-1 lg:grid-cols-12 items-stretch">
                        {{-- Image Column --}}
                        <div class="lg:col-span-7 relative min-h-[300px] lg:min-h-[450px] overflow-hidden">
                            @if ($fImg)
                                <img src="{{ $fImg }}" alt="{{ $fTitle }}" loading="lazy"
                                     class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 ease-out group-hover:scale-[1.02]">
                            @else
                                <div class="absolute inset-0 bg-gradient-to-br from-primary to-primary-light/50 flex items-center justify-center">
                                    <svg class="w-20 h-20 text-background/40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                    </svg>
                                </div>
                            @endif

                            {{-- Status Badge --}}
                            <div class="absolute top-4 start-4 z-10">
                                <span class="px-3.5 py-1.5 text-xs font-bold uppercase tracking-wider rounded-full shadow-sm text-background select-none
                                            {{ $fIsOngoing ? 'bg-primary' : 'bg-secondary' }}">
                                    {{ $fIsOngoing ? __('frontend.ongoing') : __('frontend.completed') }}
                                </span>
                            </div>
                        </div>
                        
                        {{-- Content Column --}}
                        <div class="lg:col-span-5 flex flex-col justify-between p-8 sm:p-10 space-y-6">
                            <div class="space-y-4">
                                @if ($fProgram)
                                    <span class="text-xs font-bold uppercase tracking-wider text-primary dark:text-secondary">
                                        {{ $fProgram }}
                                    </span>
                                @endif
                                
                                <h3 class="text-2xl sm:text-3xl font-black text-[#3D342A] dark:text-background leading-tight hover:text-primary transition-colors">
                                    <a href="{{ $fDetailUrl }}" class="focus:outline-none">
                                        {{ $fTitle }}
                                    </a>
                                </h3>
                                
                                @if ($fDesc)
                                    <p class="text-sm sm:text-base text-text-primary/75 dark:text-gray-300 leading-relaxed font-sans line-clamp-4 text-justify">
                                        {{ strip_tags($fDesc) }}
                                    </p>
                                @endif
                            </div>
                            
                            {{-- View Project Button --}}
                            <div class="pt-6 border-t border-background dark:border-gray-700/60">
                                <a href="{{ $fDetailUrl }}" class="inline-flex items-center gap-2 px-5 py-3 rounded-xl bg-primary hover:bg-accent text-background font-bold hover:scale-[1.02] active:scale-[0.98] transition-all duration-300 shadow-md hover:shadow-lg focus:outline-none focus-visible:ring-2 focus-visible:ring-primary">
                                    <span>{{ $locale === 'ar' ? 'تفاصيل المشروع' : 'View Project' }}</span>
                                    <svg class="w-4 h-4 transform transition-transform duration-300 group-hover:translate-x-1 rtl:group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                </article>
            </section>

            {{-- 2. Remaining Projects Grid --}}
            @if ($otherProjects->count() > 0)
                <section aria-label="{{ $locale === 'ar' ? 'مشاريعنا الأخرى' : 'Other Projects' }}">
                    <div class="text-start mb-6">
                        <h2 class="text-2xl font-black text-[#3D342A] dark:text-background flex items-center gap-2">
                            <span class="w-1.5 h-6 bg-secondary rounded-full"></span>
                            <span>{{ $locale === 'ar' ? 'مشاريعنا الأخرى' : 'Other Projects' }}</span>
                        </h2>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8"
                         x-data="{ inView: false }"
                         x-intersect.once="inView = true">
                        @foreach ($otherProjects as $index => $project)
                            @php
                                $title     = $locale === 'ar' ? $project->title_ar : ($project->title_en ?? $project->title_ar);
                                $desc      = $locale === 'ar' ? $project->description_ar : ($project->description_en ?? $project->description_ar);
                                $img       = \App\Helpers\MediaHelper::url($project, 'project_images', 'image', 'card');
                                $isOngoing = $project->project_status === \App\Models\Project::PROJECT_STATUS_ONGOING;
                                $detailUrl = route('projects.show', $project->slug);
                                $program   = $project->program ? ($locale === 'ar' ? $project->program->title_ar : ($project->program->title_en ?? $project->program->title_ar)) : null;
                            @endphp

                            {{-- Modern Editorial Project Card --}}
                            <article class="flex flex-col bg-background dark:bg-gray-800 rounded-3xl overflow-hidden border border-[#A38B54]/10 dark:border-gray-700/60 shadow-sm hover:shadow-xl hover:-translate-y-1.5 transition-all duration-300 group"
                                     :class="inView ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-6'"
                                     style="transition-delay: {{ $index * 75 }}ms; transition-duration: 700ms;">
                                
                                {{-- Card Image Wrapper --}}
                                <div class="relative aspect-[16/10] overflow-hidden bg-background dark:bg-gray-900 shrink-0">
                                    @if ($img)
                                        <img src="{{ $img }}" alt="{{ $title }}" loading="lazy"
                                             class="w-full h-full object-cover transition-transform duration-700 ease-out group-hover:scale-105 pointer-events-none">
                                    @else
                                        <div class="w-full h-full bg-gradient-to-br from-[#A38B54]/10 to-[#B49C6E]/20 dark:from-gray-700 dark:to-gray-800 flex items-center justify-center pointer-events-none">
                                            <svg class="w-12 h-12 text-primary/45" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                            </svg>
                                        </div>
                                    @endif

                                    {{-- Subtle Image Gradient --}}
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/20 via-transparent pointer-events-none" aria-hidden="true"></div>

                                    {{-- Status Badge --}}
                                    <div class="absolute top-4 end-4">
                                        <span class="px-3 py-1 text-[10px] sm:text-xs font-bold uppercase tracking-wider rounded-full shadow-sm text-background select-none
                                                    {{ $isOngoing ? 'bg-primary' : 'bg-secondary' }}">
                                            {{ $isOngoing ? __('frontend.ongoing') : __('frontend.completed') }}
                                        </span>
                                    </div>
                                </div>

                                {{-- Card Content --}}
                                <div class="flex-grow flex flex-col p-6 sm:p-7 space-y-4">
                                    @if ($program)
                                        <span class="text-xs font-bold uppercase tracking-wider text-primary dark:text-secondary select-none">
                                            {{ $program }}
                                        </span>
                                    @endif

                                    <h3 class="text-lg sm:text-xl font-bold text-[#3D342A] dark:text-background leading-snug group-hover:text-primary transition-colors duration-200">
                                        <a href="{{ $detailUrl }}" class="focus:outline-none focus-visible:underline">
                                            {{ $title }}
                                        </a>
                                    </h3>

                                    @if ($desc)
                                        <p class="text-sm text-text-primary/75 dark:text-gray-300 leading-relaxed line-clamp-3 font-sans flex-grow">
                                            {{ strip_tags($desc) }}
                                        </p>
                                    @endif

                                    {{-- Separator Line --}}
                                    <div class="h-px w-full bg-background dark:bg-gray-700/60 pt-2" aria-hidden="true"></div>

                                    {{-- CTA Button --}}
                                    <div class="flex items-center justify-between pt-2">
                                        <a href="{{ $detailUrl }}" class="inline-flex items-center gap-1.5 text-xs sm:text-sm font-bold text-[#3D342A] dark:text-background hover:text-primary dark:hover:text-secondary transition-colors group/btn focus:outline-none">
                                            <span>{{ $locale === 'ar' ? 'التفاصيل' : 'Details' }}</span>
                                            <svg class="w-4 h-4 transform transition-transform duration-300 group-hover/btn:translate-x-1 rtl:group-hover/btn:-translate-x-1 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                                            </svg>
                                        </a>
                                    </div>
                                </div>

                            </article>
                        @endforeach
                    </div>
                </section>
            @endif

            {{-- 3. Pagination --}}
            <div class="mt-12">
                <x-frontend.pagination :paginator="$projects" />
            </div>
        @else
            <x-frontend.empty-state
                :title="__('frontend.no_projects_available')"
                :description="__('frontend.projects_coming_soon')"
            />
        @endif
    </div>

</x-frontend-layout>
