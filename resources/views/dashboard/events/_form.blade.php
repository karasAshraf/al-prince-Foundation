@csrf

<div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
    {{-- Main Fields Column (2/3 width) --}}
    <div class="space-y-5 lg:col-span-2">
        <div class="rounded-xl border border-[#B49C6E]/20 bg-[#EAEAE9] p-5">
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <x-forms.input
                    name="title_ar"
                    label="{{ __('dashboard.events.event_title') }} (AR)"
                    :value="old('title_ar', $eventItem->title_ar ?? '')"
                    required
                />
                <x-forms.input
                    name="title_en"
                    label="{{ __('dashboard.events.event_title') }} (EN)"
                    :value="old('title_en', $eventItem->title_en ?? '')"
                />
            </div>

            <div class="mt-4">
                <x-forms.slug-input
                    name="slug"
                    :value="old('slug', $eventItem->slug ?? '')"
                    source-field="title_ar"
                />
            </div>

            <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2">
                <x-forms.textarea
                    name="description_ar"
                    label="{{ __('dashboard.events.description') }} (AR)"
                    :value="old('description_ar', $eventItem->description_ar ?? '')"
                    rows="4"
                />
                <x-forms.textarea
                    name="description_en"
                    label="{{ __('dashboard.events.description') }} (EN)"
                    :value="old('description_en', $eventItem->description_en ?? '')"
                    rows="4"
                />
            </div>
        </div>

        {{-- SEO Fields --}}
        <x-forms.seo-fields :seo-meta="$eventItem->seoMeta ?? null" />
    </div>

    {{-- Sidebar Column (1/3 width) --}}
    <div class="space-y-5">
        {{-- Details Card --}}
        <div class="rounded-xl border border-[#B49C6E]/20 bg-[#EAEAE9] p-5 space-y-4">
            <h3 class="text-sm font-semibold text-[#3D342A]">{{ __('dashboard.common.details') }}</h3>

            <x-forms.input
                name="order"
                label="{{ __('dashboard.common.order') }}"
                type="number"
                :value="old('order', $eventItem->order ?? 0)"
            />

            <x-forms.toggle
                name="is_active"
                label="{{ __('dashboard.common.active') }}"
                :checked="old('is_active', $eventItem->is_active ?? true)"
            />
        </div>

        {{-- Featured Image --}}
        <x-forms.media-upload
            name="image"
            url-name="media_external_link"
            :label="__('dashboard.events.featured_image')"
            :current-url="isset($eventItem) && $eventItem->id
                ? \App\Helpers\MediaHelper::url($eventItem, 'featured_image', 'image')
                : null"
            :allow-video="false"
            :allow-pdf="false"
        />

        {{-- Gallery Upload --}}
        <div class="rounded-xl border border-[#B49C6E]/20 bg-[#EAEAE9] p-5 space-y-3">
            <h3 class="border-b border-[#B49C6E]/20 pb-3 text-sm font-semibold text-[#3D342A]">
                {{ __('dashboard.events.gallery') }}
            </h3>

            @php
                $existingGallery = [];
                if (isset($eventItem) && $eventItem->id) {
                    foreach ($eventItem->getMedia('gallery') as $img) {
                        $existingGallery[] = [
                            'id'  => $img->id,
                            'url' => $img->hasGeneratedConversion('gallery_thumb') ? $img->getUrl('gallery_thumb') : $img->getUrl(),
                        ];
                    }
                }
            @endphp

            <x-forms.gallery-upload
                name="gallery"
                :label="null"
                :existing="$existingGallery"
                remove-name="remove_gallery"
                :max="10"
            />

            <p class="text-xs text-[#3D342A]/50">{{ __('dashboard.events.gallery_hint') }}</p>
        </div>
    </div>
</div>

<div class="mt-6 flex items-center gap-3">
    <x-buttons.primary type="submit">{{ __('dashboard.common.save') }}</x-buttons.primary>
    <a href="{{ route('dashboard.events.index') }}">
        <x-buttons.secondary type="button">{{ __('dashboard.common.cancel') }}</x-buttons.secondary>
    </a>
</div>
