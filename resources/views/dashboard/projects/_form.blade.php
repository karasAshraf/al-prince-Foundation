@csrf

<div class="grid grid-cols-1 gap-6 lg:grid-cols-3">

    {{-- ============ MAIN COLUMN ============ --}}
    <div class="space-y-5 lg:col-span-2">

        {{-- Basic Info --}}
        <div class="rounded-xl border border-[#B49C6E]/20 bg-[#EAEAE9] p-5">
            <h3 class="mb-4 text-sm font-semibold text-[#3D342A]">{{ __('dashboard.projects.show') }}</h3>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <x-forms.input
                    name="title_ar"
                    label="{{ __('dashboard.projects.project_title') }} (AR)"
                    :value="$project->title_ar ?? ''"
                    required
                />
                <x-forms.input
                    name="title_en"
                    label="{{ __('dashboard.projects.project_title') }} (EN)"
                    :value="$project->title_en ?? ''"
                />
            </div>

            <div class="mt-4">
                <x-forms.slug-input
                    name="slug"
                    :value="$project->slug ?? ''"
                    source-field="title_ar"
                />
            </div>
        </div>

        {{-- Description & Goals --}}
        <div class="rounded-xl border border-[#B49C6E]/20 bg-[#EAEAE9] p-5">
            <h3 class="mb-4 text-sm font-semibold text-[#3D342A]">{{ __('dashboard.projects.description') }}</h3>

            <div class="mb-4">
                <x-forms.rich-editor
                    name="description_ar"
                    label="{{ __('dashboard.projects.description') }} (AR)"
                    :value="$project->description_ar ?? ''"
                />
            </div>
            <div class="mb-4">
                <x-forms.rich-editor
                    name="description_en"
                    label="{{ __('dashboard.projects.description') }} (EN)"
                    :value="$project->description_en ?? ''"
                />
            </div>
            <div class="mb-4">
                <x-forms.rich-editor
                    name="goal_ar"
                    label="{{ __('dashboard.programs.goals') }} (AR)"
                    :value="$project->goal_ar ?? ''"
                />
            </div>
            <x-forms.rich-editor
                name="goal_en"
                label="{{ __('dashboard.programs.goals') }} (EN)"
                :value="$project->goal_en ?? ''"
            />
        </div>

        {{-- SEO --}}
        <x-forms.seo-fields :seo-meta="$project->seoMeta ?? null" />

    </div>

    {{-- ============ SIDEBAR COLUMN ============ --}}
    <div class="space-y-5">

        {{-- Program & Status --}}
        <div class="rounded-xl border border-[#B49C6E]/20 bg-[#EAEAE9] p-5">
            <h3 class="mb-4 text-sm font-semibold text-[#3D342A]">{{ __('dashboard.common.status') }}</h3>
            <div class="space-y-4">

                {{-- Program selector --}}
                <x-forms.select
                    name="program_id"
                    label="{{ __('dashboard.programs.single') }}"
                    :options="$programs->toArray()"
                    :selected="$project->program_id ?? ''"
                />

                {{-- Publish status --}}
                <x-forms.select
                    name="status"
                    label="{{ __('dashboard.common.status') }}"
                    :options="['draft' => __('dashboard.common.draft'), 'published' => __('dashboard.common.published')]"
                    :selected="$project->status ?? 'draft'"
                    required
                />

                {{-- Execution status --}}
                <x-forms.select
                    name="project_status"
                    label="{{ __('dashboard.projects.status') }}"
                    :options="['ongoing' => __('dashboard.projects.in_progress'), 'completed' => __('dashboard.projects.completed')]"
                    :selected="$project->project_status ?? 'ongoing'"
                    required
                />

            </div>
        </div>

        {{-- Dates --}}
        <div class="rounded-xl border border-[#B49C6E]/20 bg-[#EAEAE9] p-5">
            <h3 class="mb-4 text-sm font-semibold text-[#3D342A]">{{ __('dashboard.projects.start_date') }}</h3>
            <div class="space-y-4">
                <x-forms.date-picker
                    name="start_date"
                    label="{{ __('dashboard.projects.start_date') }}"
                    :value="$project->start_date?->format('Y-m-d')"
                />
                <x-forms.date-picker
                    name="end_date"
                    label="{{ __('dashboard.projects.end_date') }}"
                    :value="$project->end_date?->format('Y-m-d')"
                />
            </div>
        </div>

        {{-- External Link --}}
       <!-- <div class="rounded-xl border border-[#B49C6E]/20 bg-[#EAEAE9] p-5">
            <x-forms.input
                name="external_link"
                label="رابط التوجيه الخارجي (اختياري)"
                type="url"
                :value="old('external_link', $project->external_link ?? '')"
                placeholder="https://example.com"
                hint="عند إدخال رابط هنا، سيتم توجيه الزوار مباشرة لهذا الرابط بدلاً من عرض صفحة التفاصيل الداخلية."
            />
        </div>-->

        <x-media-upload
            name="image"
            url-name="media_external_link"
            :current-url="isset($project) ? \App\Helpers\MediaHelper::url($project, 'project_images', 'image') : null"
            :current-external-url="$project->external_link ?? null"
        />


        {{-- Actions --}}
        <div class="flex flex-col gap-3">
            <x-buttons.primary type="submit">{{ __('dashboard.common.save') }}</x-buttons.primary>
            <a href="{{ route('dashboard.projects.index') }}">
                <x-buttons.secondary type="button" class="w-full justify-center">{{ __('dashboard.common.cancel') }}</x-buttons.secondary>
            </a>
        </div>

    </div>
</div>
