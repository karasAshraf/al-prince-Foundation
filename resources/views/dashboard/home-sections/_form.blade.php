@csrf

<div x-data="{
    type: @js(old('type', $section->type ?? 'hero_slider')),
    isCounter() {
        return this.type === 'counter' || this.type === 'counters';
    },
    isLatestNews() {
        return this.type === 'latest_news';
    }
}">
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">

        {{-- ============ MAIN COLUMN ============ --}}
        <div class="space-y-5 lg:col-span-2">

            <div class="rounded-xl border border-[#B49C6E]/20 bg-[#EAEAE9] p-5 shadow-sm space-y-4">
                <h2 class="text-base font-semibold text-[#3D342A] border-b border-[#B49C6E]/20 pb-3 mb-4">{{ __('dashboard.common.details') }}</h2>

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-[#3D342A]">
                            {{ __('dashboard.common.type') }} <span class="text-red-500">*</span>
                        </label>
                        <select
                            name="type"
                            x-model="type"
                            required
                            class="w-full rounded-lg border border-[#B49C6E]/40 bg-[#EAEAE9] px-3.5 py-2.5 text-sm text-[#3D342A] focus:border-[#A38B54] focus:outline-none"
                        >
                           
                            
                            <option value="hero_slider">سلايدر هيرو الرئيسي (Hero Slider)</option>
                            <option value="about_preview">قسم نبذة عنا (About Preview)</option>
                            <option value="service_section">قسم الخدمات (Services Preview)</option>
                            <option value="projects_preview">قسم المشاريع (Projects Preview)</option>
                            <option value="counters">قسم العدادات (Counters)</option>
                            <option value="latest_news">قسم أحدث الأخبار (Latest News)</option>
                            <option value="home_section">قسم رئيسي عام (Home Section)</option>
                        </select>
                        @error('type')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Subtitle (Label): Hidden for Counter --}}
                    <div x-show="!isCounter()" class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <x-forms.input
                            name="label_ar"
                            label="{{ __('dashboard.home_sections.subtitle') }} (AR)"
                            :value="old('label_ar', $section->label_ar ?? '')"
                        />
                        <x-forms.input
                            name="label_en"
                            label="{{ __('dashboard.home_sections.subtitle') }} (EN)"
                            :value="old('label_en', $section->label_en ?? '')"
                        />
                    </div>
                </div>

                {{-- Counter Specific Fields (Counter Number & Counter Icon) --}}
                <div x-show="isCounter()" class="grid grid-cols-1 gap-4 sm:grid-cols-2 pt-2 border-t border-[#B49C6E]/20">
                    <div>
                        <x-forms.input
                            name="counter_number"
                            label="رقم العداد (Counter Number)"
                            :value="old('counter_number', $section->counter_number ?? '')"
                            placeholder="مثال: 150+"
                        />
                    </div>
                    <div>
                        <x-icon-picker
                            name="counter_icon"
                            label="رمز / فئة الأيقونة (Counter Icon)"
                            :value="old('counter_icon', $section->counter_icon ?? '')"
                        />
                    </div>
                </div>

                {{-- Optional Section Icon (Hidden for hero_slider & counter) --}}
                <div x-show="type !== 'hero_slider' && !isCounter()">
                    <x-icon-picker
                        name="icon"
                        label="أيقونة القسم (Section Icon)"
                        :value="old('icon', $section->icon ?? '')"
                        hint="أيقونة إضافية مخصصة تعبر عن القسم في اللوحة أو العرض (اختياري)"
                    />
                </div>

                {{-- Section Title (AR / EN): Shown for all types --}}
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <x-forms.input
                        name="title_ar"
                        label="{{ __('dashboard.home_sections.section_title') }} (AR)"
                        :value="old('title_ar', $section->title_ar ?? '')"
                    />
                    <x-forms.input
                        name="title_en"
                        label="{{ __('dashboard.home_sections.section_title') }} (EN)"
                        :value="old('title_en', $section->title_en ?? '')"
                    />
                </div>

                {{-- Person Name (AR / EN): Optional, shown near section title --}}
                <div x-show="type === 'home_section'" class="grid grid-cols-1 gap-4 sm:grid-cols-2 pt-2 border-t border-[#B49C6E]/10">
                    <x-forms.input
                        name="person_name_ar"
                        label="اسم الشخص (AR)"
                        :value="old('person_name_ar', $section->person_name_ar ?? '')"
                        placeholder="مثال: د. خالد العتيبي - رئيس مجلس الأمناء"
                    />
                    <x-forms.input
                        name="person_name_en"
                        label="اسم الشخص (EN)"
                        :value="old('person_name_en', $section->person_name_en ?? '')"
                        placeholder="Example: Dr. Khalid Al-Otaibi - Chairman"
                    />
                </div>

                {{-- Description (AR / EN): Hidden for Counter --}}
                <div x-show="!isCounter()" class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <x-forms.textarea
                        name="description_ar"
                        label="{{ __('dashboard.common.details') }} (AR)"
                        :value="old('description_ar', $section->description_ar ?? '')"
                        rows="3"
                    />
                    <x-forms.textarea
                        name="description_en"
                        label="{{ __('dashboard.common.details') }} (EN)"
                        :value="old('description_en', $section->description_en ?? '')"
                        rows="3"
                    />
                </div>

                {{-- Extra Link / External URL: Hidden for Counter --}}
                <div x-show="!isCounter()">
                    <x-forms.input
                        name="extra_link"
                        label="رابط إضافي / زر (Extra Link)"
                        type="url"
                        :value="old('extra_link', $section->extra_link ?? '')"
                        placeholder="https://example.com"
                    />
                </div>
            </div>

        </div>

        {{-- ============ SIDEBAR COLUMN ============ --}}
        <div class="space-y-5">

            <div class="rounded-xl border border-[#B49C6E]/20 bg-[#EAEAE9] p-5 shadow-sm space-y-4">
                <h3 class="text-sm font-semibold text-[#3D342A] border-b border-[#B49C6E]/20 pb-3">{{ __('dashboard.common.status') }}</h3>

                <x-forms.input
                    name="order"
                    label="{{ __('dashboard.common.order') }}"
                    type="number"
                    min="0"
                    :value="old('order', $section->order ?? 0)"
                />

                <div class="pt-2">
                    <x-forms.checkbox
                        name="is_active"
                        label="{{ __('dashboard.common.active') }}"
                        :checked="old('is_active', $section->is_active ?? true)"
                    />
                </div>
            </div>

            {{-- Media Component --}}
            <div x-show="!isCounter() || true">
                <x-media-upload
                    name="image"
                    url-name="external_link"
                    label="وسائط القسم / أيقونة العداد"
                    :current-url="isset($section) ? \App\Helpers\MediaHelper::url($section, 'home_section_images', 'image') : null"
                    :current-external-url="$section->extra_link ?? null"
                />
            </div>

            <div class="flex flex-col gap-3">
                <x-buttons.primary type="submit" class="w-full justify-center">{{ __('dashboard.common.save') }}</x-buttons.primary>
                <a href="{{ route('dashboard.home-sections.index') }}" class="w-full">
                    <x-buttons.secondary type="button" class="w-full justify-center">{{ __('dashboard.common.cancel') }}</x-buttons.secondary>
                </a>
            </div>

        </div>

    </div>
</div>

