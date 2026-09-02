@csrf

<div class="grid grid-cols-1 gap-6 lg:grid-cols-3">

    {{-- ============ MAIN COLUMN ============ --}}
    <div class="space-y-5 lg:col-span-2">

        {{-- Basic Information --}}
        <div class="rounded-xl border border-[#B49C6E]/20 bg-secondary p-5 shadow-sm space-y-4">
            <h2 class="text-base font-semibold text-[#3D342A] border-b border-[#B49C6E]/20 pb-3 mb-4">{{ __('dashboard.hero_slides.slide_details') }}</h2>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <x-forms.input
                    name="title_ar"
                    :label="__('dashboard.hero_slides.title_ar')"
                    :value="$slide->title_ar ?? ''"
                />
                <x-forms.input
                    name="title_en"
                    :label="__('dashboard.hero_slides.title_en')"
                    :value="$slide->title_en ?? ''"
                />
            </div>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div class="space-y-1">
                    <label class="text-xs font-semibold text-[#3D342A]">{{ __('dashboard.hero_slides.subtitle_ar') }}</label>
                    <textarea name="subtitle_ar" rows="3" class="w-full rounded-lg border border-[#B49C6E]/40 bg-secondary px-3.5 py-2 text-sm text-[#3D342A] focus:border-[#A38B54] focus:outline-none">{{ $slide->subtitle_ar ?? '' }}</textarea>
                </div>
                <div class="space-y-1">
                    <label class="text-xs font-semibold text-[#3D342A]">{{ __('dashboard.hero_slides.subtitle_en') }}</label>
                    <textarea name="subtitle_en" rows="3" class="w-full rounded-lg border border-[#B49C6E]/40 bg-secondary px-3.5 py-2 text-sm text-[#3D342A] focus:border-[#A38B54] focus:outline-none">{{ $slide->subtitle_en ?? '' }}</textarea>
                </div>
            </div>
        </div>

        {{-- Button Details --}}
        <div class="rounded-xl border border-[#B49C6E]/20 bg-secondary p-5 shadow-sm space-y-4">
            <h2 class="text-base font-semibold text-[#3D342A] border-b border-[#B49C6E]/20 pb-3 mb-2">{{ __('dashboard.hero_slides.button_details') }}</h2>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                <x-forms.input
                    name="button_text_ar"
                    :label="__('dashboard.hero_slides.button_text_ar')"
                    :value="$slide->button_text_ar ?? ''"
                />
                <x-forms.input
                    name="button_text_en"
                    :label="__('dashboard.hero_slides.button_text_en')"
                    :value="$slide->button_text_en ?? ''"
                />
                <x-forms.input
                    name="button_url"
                    :label="__('dashboard.hero_slides.button_url')"
                    type="url"
                    :value="$slide->button_url ?? ''"
                    placeholder="https://example.com"
                />
            </div>
        </div>

    </div>

    {{-- ============ SIDEBAR COLUMN ============ --}}
    <div class="space-y-5">

        {{-- Configuration Card --}}
        <div class="rounded-xl border border-[#B49C6E]/20 bg-secondary p-5 shadow-sm space-y-4">
            <h3 class="text-sm font-semibold text-[#3D342A] border-b border-[#B49C6E]/20 pb-3">{{ __('dashboard.hero_slides.settings') }}</h3>

            <x-forms.select
                name="placement"
                :label="__('dashboard.hero_slides.placement')"
                :options="\App\Helpers\NavigationHelper::getPlacements()"
                :selected="$slide->placement ?? 'home'"
                required
            />

            <x-forms.input
                name="order"
                :label="__('dashboard.hero_slides.order')"
                type="number"
                :value="$slide->order ?? 0"
                required
            />

            <div class="flex items-center gap-2 pt-2">
                <input type="checkbox" name="is_active" id="is_active" value="1" {{ ($slide->is_active ?? true) ? 'checked' : '' }} class="rounded border-[#B49C6E]/40 text-[#A38B54] focus:ring-[#A38B54]">
                <label for="is_active" class="text-xs font-semibold text-[#3D342A]">{{ __('dashboard.hero_slides.is_active_label') }}</label>
            </div>
        </div>

        @php
            $currentExternalUrl = null;
            $currentUrl = null;
            if (isset($slide) && $slide->id) {
                $rawUrl = \App\Helpers\MediaHelper::url($slide, 'hero_slide_images', 'image');
                if (\App\Helpers\MediaHelper::isExternal($rawUrl)) {
                    $currentExternalUrl = $rawUrl;
                } else {
                    $currentUrl = $rawUrl;
                }
            }
        @endphp

        {{-- Image Upload --}}
        <x-media-upload
            name="image"
            url-name="media_external_link"
            :current-url="$currentUrl"
            :current-external-url="$currentExternalUrl"
            :allow-video="true"
            :allow-pdf="false"
            :allow-external="true"
        />

        {{-- Action Buttons --}}
        <div class="flex flex-col gap-3">
            <x-buttons.primary type="submit" class="w-full justify-center">{{ __('dashboard.common.save') }}</x-buttons.primary>
            <a href="{{ route('dashboard.hero-slides.index') }}" class="w-full">
                <x-buttons.secondary type="button" class="w-full justify-center">{{ __('dashboard.common.cancel') }}</x-buttons.secondary>
            </a>
        </div>

    </div>

</div>
