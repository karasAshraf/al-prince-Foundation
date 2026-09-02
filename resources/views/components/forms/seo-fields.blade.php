@props([
    'seoMeta' => null,
])

<div class="rounded-xl border border-[#B49C6E]/30 bg-secondary/10 p-5">
    <h3 class="mb-4 flex items-center gap-2 text-sm font-semibold text-[#3D342A]">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-[#A38B54]" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
        </svg>
        {{ __('dashboard.seo.title') }}
    </h3>

    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
        <x-forms.input
            name="meta_title_ar"
            label="{{ __('dashboard.seo.meta_title') }} (AR)"
            :value="old('meta_title_ar', $seoMeta->meta_title_ar ?? '')"
        />

        <x-forms.input
            name="meta_title_en"
            label="{{ __('dashboard.seo.meta_title') }} (EN)"
            :value="old('meta_title_en', $seoMeta->meta_title_en ?? '')"
        />

        <div class="sm:col-span-2">
            <x-forms.textarea
                name="meta_description_ar"
                label="{{ __('dashboard.seo.meta_description') }} (AR)"
                :value="old('meta_description_ar', $seoMeta->meta_description_ar ?? '')"
                rows="2"
            />
        </div>

        <div class="sm:col-span-2">
            <x-forms.textarea
                name="meta_description_en"
                label="{{ __('dashboard.seo.meta_description') }} (EN)"
                :value="old('meta_description_en', $seoMeta->meta_description_en ?? '')"
                rows="2"
            />
        </div>

        <x-forms.input
            name="meta_keywords"
            label="{{ __('dashboard.seo.meta_keywords') }}"
            :value="old('meta_keywords', $seoMeta->meta_keywords ?? '')"
        />

        <x-forms.input
            name="canonical_url"
            label="Canonical URL"
            :value="old('canonical_url', $seoMeta->canonical_url ?? '')"
        />
    </div>
</div>
