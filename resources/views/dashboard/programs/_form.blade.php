@csrf

<div class="grid grid-cols-1 gap-6 lg:grid-cols-3">

    {{-- ============ MAIN COLUMN ============ --}}
    <div class="space-y-5 lg:col-span-2">

        {{-- Basic Info Card --}}
        <div class="rounded-xl border border-[#B49C6E]/20 bg-secondary p-5">

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <x-forms.input
                    name="title_ar"
                    label="{{ __('dashboard.programs.program_title') }} (AR)"
                    :value="$program->title_ar ?? ''"
                    required
                />
                <x-forms.input
                    name="title_en"
                    label="{{ __('dashboard.programs.program_title') }} (EN)"
                    :value="$program->title_en ?? ''"
                />
            </div>

            <div class="mt-4">
                <x-forms.slug-input
                    name="slug"
                    :value="$program->slug ?? ''"
                    source-field="title_ar"
                />
            </div>

            <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2">
                <x-forms.textarea
                    name="summary_ar"
                    label="{{ __('dashboard.news.summary') }} (AR)"
                    :value="old('summary_ar', $program->summary_ar ?? '')"
                    rows="3"
                />
                <x-forms.textarea
                    name="summary_en"
                    label="{{ __('dashboard.news.summary') }} (EN)"
                    :value="old('summary_en', $program->summary_en ?? '')"
                    rows="3"
                />
            </div>

        </div>

        {{-- Content Card --}}
        <div class="rounded-xl border border-[#B49C6E]/20 bg-secondary p-5">
            <div class="mb-4">
                <x-forms.rich-editor
                    name="description_ar"
                    label="{{ __('dashboard.programs.description') }} (AR)"
                    :value="$program->description_ar ?? ''"
                    required
                />
            </div>
            <x-forms.rich-editor
                name="description_en"
                label="{{ __('dashboard.programs.description') }} (EN)"
                :value="$program->description_en ?? ''"
            />
        </div>

        {{-- SEO Fields --}}
        <x-forms.seo-fields :seo-meta="$program->seoMeta ?? null" />

    </div>

    {{-- ============ SIDEBAR COLUMN ============ --}}
    <div class="space-y-5">

        {{-- Publish Settings --}}
        <div class="rounded-xl border border-[#B49C6E]/20 bg-secondary p-5">
            <h3 class="mb-4 text-sm font-semibold text-[#3D342A]">{{ __('dashboard.common.status') }}</h3>

            <div class="space-y-4">
                <x-forms.select
                    name="status"
                    label="{{ __('dashboard.common.status') }}"
                    :options="['active' => __('dashboard.common.active'), 'inactive' => __('dashboard.common.inactive')]"
                    :selected="$program->status ?? 'active'"
                    required
                />

                <x-forms.input
                    name="order"
                    label="{{ __('dashboard.common.order') }}"
                    type="number"
                    min="0"
                    :value="$program->order ?? 0"
                />

        
            </div>
        </div>

        <x-media-upload
            name="image"
            url-name="media_external_link"
            :current-url="isset($program) ? \App\Helpers\MediaHelper::url($program, 'program_images', 'image') : null"
            :current-external-url="$program->external_link ?? null"
        />



        {{-- Action Buttons --}}
        <div class="flex flex-col gap-3">
            <x-buttons.primary type="submit">{{ __('dashboard.common.save') }}</x-buttons.primary>
            <a href="{{ route('dashboard.programs.index') }}">
                <x-buttons.secondary type="button" class="w-full justify-center">{{ __('dashboard.common.cancel') }}</x-buttons.secondary>
            </a>
        </div>

    </div>

</div>
