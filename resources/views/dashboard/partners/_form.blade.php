@csrf

<div class="grid grid-cols-1 gap-6 lg:grid-cols-3 items-start">
    <div class="space-y-5 lg:col-span-2">
        <div class="rounded-xl border border-[#B49C6E]/20 bg-[#EAEAE9] p-5">
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <x-forms.input
                    name="name_ar"
                    label="{{ __('dashboard.partners.partner_name') }} (AR)"
                    :value="old('name_ar', $partnerItem->name_ar ?? '')"
                    required
                />
                <x-forms.input
                    name="name_en"
                    label="{{ __('dashboard.partners.partner_name') }} (EN)"
                    :value="old('name_en', $partnerItem->name_en ?? '')"
                />
            </div>
            
            <div class="mt-4">
                <x-forms.input
                    name="external_link"
                    label="رابط موقع الشريك (اختياري)"
                    type="url"
                    :value="old('external_link', $partnerItem->external_link ?? '')"
                />
            </div>
        </div>
    </div>

    <div class="space-y-5">
        <div class="rounded-xl border border-[#B49C6E]/20 bg-[#EAEAE9] p-5 space-y-4">
            <h3 class="text-sm font-semibold text-[#3D342A]">{{ __('dashboard.common.details') }}</h3>

            <x-forms.input
                name="order"
                label="{{ __('dashboard.common.order') }}"
                type="number"
                :value="old('order', $partnerItem->order ?? 0)"
            />

            <x-forms.toggle
                name="is_active"
                label="{{ __('dashboard.common.active') }}"
                :checked="old('is_active', $partnerItem->is_active ?? true)"
            />
        </div>

        <x-forms.media-upload
            name="image"
            url-name="media_external_link"
            :current-url="isset($partnerItem) && $partnerItem->id ? \App\Helpers\MediaHelper::url($partnerItem, 'partner_logos', 'image') : null"
            :current-external-url="$partnerItem->external_link ?? null"
            :allow-video="false"
            :allow-pdf="false"
        />
    </div>
</div>

<div class="mt-6 flex items-center gap-3">
    <x-buttons.primary type="submit">{{ __('dashboard.common.save') }}</x-buttons.primary>
    <a href="{{ route('dashboard.partners.index') }}">
        <x-buttons.secondary type="button">{{ __('dashboard.common.cancel') }}</x-buttons.secondary>
    </a>
</div>
