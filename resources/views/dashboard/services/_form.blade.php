@csrf

<div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
    <div class="space-y-5 lg:col-span-2">
        <div class="rounded-xl border border-[#B49C6E]/20 bg-[#EAEAE9] p-5">
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <x-forms.input
                    name="title_ar"
                    label="{{ __('dashboard.services.service_title') }} (AR)"
                    :value="old('title_ar', $serviceItem->title_ar ?? '')"
                    required
                />
                <x-forms.input
                    name="title_en"
                    label="{{ __('dashboard.services.service_title') }} (EN)"
                    :value="old('title_en', $serviceItem->title_en ?? '')"
                />
            </div>

            <div class="mt-4">
                <x-forms.slug-input
                    name="slug"
                    :value="old('slug', $serviceItem->slug ?? '')"
                    source-field="title_ar"
                />
            </div>

            <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2">
                <x-forms.textarea
                    name="description_ar"
                    label="{{ __('dashboard.services.description') }} (AR)"
                    :value="old('description_ar', $serviceItem->description_ar ?? '')"
                    rows="4"
                />
                <x-forms.textarea
                    name="description_en"
                    label="{{ __('dashboard.services.description') }} (EN)"
                    :value="old('description_en', $serviceItem->description_en ?? '')"
                    rows="4"
                />
            </div>
        </div>

        <x-forms.seo-fields :seo-meta="$serviceItem->seoMeta ?? null" />
    </div>

    <div class="space-y-5">
        <div class="rounded-xl border border-[#B49C6E]/20 bg-[#EAEAE9] p-5 space-y-4">
            <h3 class="text-sm font-semibold text-[#3D342A]">{{ __('dashboard.common.details') }}</h3>

            <x-icon-picker
                name="icon"
                label="{{ __('dashboard.services.icon') }}"
                :value="old('icon', $serviceItem->icon ?? '')"
            />

           

            <x-forms.input
                name="order"
                label="{{ __('dashboard.common.order') }}"
                type="number"
                :value="old('order', $serviceItem->order ?? 0)"
            />

            <x-forms.toggle
                name="is_active"
                label="{{ __('dashboard.common.active') }}"
                :checked="old('is_active', $serviceItem->is_active ?? true)"
            />
        </div>

        <x-media-upload
            name="image"
            url-name="media_external_link"
            :current-url="isset($serviceItem) ? \App\Helpers\MediaHelper::url($serviceItem, 'service_images', 'image') : null"
            :current-external-url="$serviceItem->external_link ?? null"
        />


    </div>
</div>

<div class="mt-6 flex items-center gap-3">
    <x-buttons.primary type="submit">{{ __('dashboard.common.save') }}</x-buttons.primary>
    <a href="{{ route('dashboard.services.index') }}">
        <x-buttons.secondary type="button">{{ __('dashboard.common.cancel') }}</x-buttons.secondary>
    </a>
</div>
