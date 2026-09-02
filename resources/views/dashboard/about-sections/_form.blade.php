@csrf

<div class="grid grid-cols-1 gap-6 lg:grid-cols-3">

    {{-- ============ MAIN COLUMN ============ --}}
    <div class="space-y-5 lg:col-span-2">

        {{-- Basic Information --}}
        <div class="rounded-xl border border-[#B49C6E]/20 bg-secondary p-5 shadow-sm space-y-4">
            <h2 class="text-base font-semibold text-[#3D342A] border-b border-[#B49C6E]/20 pb-3 mb-4">{{ __('dashboard.about_sections.section_title') }}</h2>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <x-forms.input
                    name="title_ar"
                    label="{{ __('dashboard.about_sections.section_title') }} (AR)"
                    :value="$aboutSection->title_ar ?? ''"
                    required
                />
                <x-forms.input
                    name="title_en"
                    label="{{ __('dashboard.about_sections.section_title') }} (EN)"
                    :value="$aboutSection->title_en ?? ''"
                />
            </div>

            <div class="mt-4">
                <x-forms.slug-input
                    name="slug"
                    :value="$aboutSection->slug ?? ''"
                    source-field="title_ar"
                />
            </div>
        </div>

        {{-- Content Card --}}
        <div class="rounded-xl border border-[#B49C6E]/20 bg-secondary p-5 shadow-sm space-y-5">
            <h2 class="text-base font-semibold text-[#3D342A] border-b border-[#B49C6E]/20 pb-3 mb-2">{{ __('dashboard.about_sections.content') }}</h2>

            <x-forms.rich-editor
                name="description_ar"
                label="{{ __('dashboard.about_sections.content') }} (AR)"
                :value="$aboutSection->description_ar ?? ''"
                required
            />

            <x-forms.rich-editor
                name="description_en"
                label="{{ __('dashboard.about_sections.content') }} (EN)"
                :value="$aboutSection->description_en ?? ''"
            />
        </div>


       
        {{-- SEO Card --}}
        <x-forms.seo-fields :seo-meta="$aboutSection->seoMeta ?? null" />

    </div>

    {{-- ============ SIDEBAR COLUMN ============ --}}
    <div class="space-y-5">

        {{-- Status Card --}}
        <div class="rounded-xl border border-[#B49C6E]/20 bg-secondary p-5 shadow-sm space-y-4">
            <h3 class="text-sm font-semibold text-[#3D342A] border-b border-[#B49C6E]/20 pb-3">{{ __('dashboard.common.status') }}</h3>

            <x-forms.select
                name="status"
                label="{{ __('dashboard.common.status') }}"
                :options="['draft' => __('dashboard.common.draft'), 'published' => __('dashboard.common.published')]"
                :selected="$aboutSection->status ?? 'published'"
                required
            />

            <x-forms.input
                name="order"
                label="{{ __('dashboard.common.order') }}"
                type="number"
                min="0"
                :value="$aboutSection->order ?? 0"
            />
        </div>

        <x-media-upload
            name="image"
            url-name="media_external_link"
            :current-url="isset($aboutSection) ? \App\Helpers\MediaHelper::url($aboutSection, 'about_images', 'image') : null"
            :current-external-url="$aboutSection->external_link ?? ($aboutSection->video ?? null)"
        />


        {{-- Action Buttons --}}
        <div class="flex flex-col gap-3">
            <x-buttons.primary type="submit" class="w-full justify-center">{{ __('dashboard.common.save') }}</x-buttons.primary>
            <a href="{{ route('dashboard.about-sections.index') }}" class="w-full">
                <x-buttons.secondary type="button" class="w-full justify-center">{{ __('dashboard.common.cancel') }}</x-buttons.secondary>
            </a>
        </div>

    </div>

</div>
