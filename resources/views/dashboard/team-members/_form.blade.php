@csrf

<div class="grid grid-cols-1 gap-6 lg:grid-cols-3">

    {{-- ============ MAIN COLUMN ============ --}}
    <div class="space-y-5 lg:col-span-2">

        {{-- Basic Information --}}
        <div class="rounded-xl border border-[#B49C6E]/20 bg-secondary p-5 shadow-sm space-y-4">
            <h2 class="text-base font-semibold text-[#3D342A] border-b border-[#B49C6E]/20 pb-3 mb-4">{{ __('dashboard.common.details') }}</h2>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <x-forms.input
                    name="name_ar"
                    label="{{ __('dashboard.team_members.name') }} (AR)"
                    :value="$member->name_ar ?? ''"
                    required
                />
                <x-forms.input
                    name="name_en"
                    label="{{ __('dashboard.team_members.name') }} (EN)"
                    :value="$member->name_en ?? ''"
                />
            </div>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <x-forms.input
                    name="position_ar"
                    label="{{ __('dashboard.team_members.job_title') }} (AR)"
                    :value="$member->position_ar ?? ''"
                    required
                />
                <x-forms.input
                    name="position_en"
                    label="{{ __('dashboard.team_members.job_title') }} (EN)"
                    :value="$member->position_en ?? ''"
                />
            </div>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <x-forms.textarea
                    name="bio_ar"
                    label="{{ __('dashboard.team_members.bio') }} (AR)"
                    :value="$member->bio_ar ?? ''"
                    rows="4"
                />
                <x-forms.textarea
                    name="bio_en"
                    label="{{ __('dashboard.team_members.bio') }} (EN)"
                    :value="$member->bio_en ?? ''"
                    rows="4"
                />
            </div>
        </div>

    </div>

    {{-- ============ SIDEBAR COLUMN ============ --}}
    <div class="space-y-5">

        {{-- Status & Type --}}
        <div class="rounded-xl border border-[#B49C6E]/20 bg-secondary p-5 shadow-sm space-y-4">
            <h3 class="text-sm font-semibold text-[#3D342A] border-b border-[#B49C6E]/20 pb-3">{{ __('dashboard.common.type') }}</h3>

            <x-forms.select
                name="type"
                label="{{ __('dashboard.common.type') }}"
                :options="['executive' => __('dashboard.team_members.type_executive'), 'board' => __('dashboard.team_members.type_board')]"
                :selected="$member->type ?? (request('type') ?? 'executive')"
                required
            />

            <x-forms.input
                name="order"
                label="{{ __('dashboard.common.order') }}"
                type="number"
                min="0"
                :value="$member->order ?? 0"
            />

            <div class="pt-2">
                <x-forms.checkbox
                    name="is_active"
                    label="{{ __('dashboard.common.active') }}"
                    :checked="$member->is_active ?? true"
                />
            </div>
        </div>

        <x-media-upload
            name="photo"
            url-name="external_link"
            label="الصورة / الفيديو الشخصي"
            :current-url="isset($member) ? \App\Helpers\MediaHelper::url($member, 'team_photos', 'image') : null"
            :current-external-url="$member->external_link ?? null"
        />



        {{-- Action Buttons --}}
        <div class="flex flex-col gap-3">
            <x-buttons.primary type="submit" class="w-full justify-center">{{ __('dashboard.common.save') }}</x-buttons.primary>
            <a href="{{ route('dashboard.team-members.index') }}" class="w-full">
                <x-buttons.secondary type="button" class="w-full justify-center">{{ __('dashboard.common.cancel') }}</x-buttons.secondary>
            </a>
        </div>

    </div>

</div>
