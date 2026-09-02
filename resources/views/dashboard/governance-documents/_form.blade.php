@csrf

<div class="grid grid-cols-1 gap-6 lg:grid-cols-3">

    {{-- ============ MAIN COLUMN ============ --}}
    <div class="space-y-5 lg:col-span-2">

        <div class="rounded-xl border border-[#B49C6E]/20 bg-secondary p-5 shadow-sm space-y-4">
            <h2 class="text-base font-semibold text-[#3D342A] border-b border-[#B49C6E]/20 pb-3 mb-4">{{ __('dashboard.common.details') }}</h2>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <x-forms.input
                    name="title_ar"
                    label="{{ __('dashboard.governance_documents.document_title') }} (AR)"
                    :value="$document->title_ar ?? ''"
                    required
                />
                <x-forms.input
                    name="title_en"
                    label="{{ __('dashboard.governance_documents.document_title') }} (EN)"
                    :value="$document->title_en ?? ''"
                />
            </div>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <x-forms.select
                    name="category"
                    label="{{ __('dashboard.governance_documents.category') }}"
                    :options="[
                        'policies' => __('dashboard.governance_documents.categories.policies'),
                        'financial_reports' => __('dashboard.governance_documents.categories.financial_reports'),
                        'achievement_reports' => __('dashboard.governance_documents.categories.achievement_reports')
                    ]"
                    :selected="$document->category ?? 'policies'"
                    required
                />

                <x-forms.input
                    name="fiscal_year"
                    label="{{ __('dashboard.governance_documents.fiscal_year') }}"
                    type="number"
                    min="2000"
                    max="2100"
                    :value="$document->fiscal_year ?? date('Y')"
                    required
                />
            </div>
        </div>

        {{-- Document File / Media Upload --}}
        <div class="rounded-xl border border-[#B49C6E]/20 bg-secondary p-5 shadow-sm space-y-4">
            <x-media-upload
                name="file"
                url-name="external_link"
                label="ملف / وسائط المستند (PDF, صور, فيديو)"
                :current-url="isset($document->file_path) ? $document->file_path : null"
                :current-external-url="isset($document->file_path) && filter_var($document->file_path, FILTER_VALIDATE_URL) ? $document->file_path : null"
            />
        </div>
    </div>

    {{-- ============ SIDEBAR COLUMN ============ --}}
    <div class="space-y-5">

        <div class="rounded-xl border border-[#B49C6E]/20 bg-secondary p-5 shadow-sm space-y-4">
            <h3 class="text-sm font-semibold text-[#3D342A] border-b border-[#B49C6E]/20 pb-3">{{ __('dashboard.common.status') }}</h3>

            <x-forms.input
                name="order"
                label="{{ __('dashboard.common.order') }}"
                type="number"
                min="0"
                :value="$document->order ?? 0"
            />

            <div class="pt-2">
                <x-forms.checkbox
                    name="is_active"
                    label="{{ __('dashboard.common.active') }}"
                    :checked="$document->is_active ?? true"
                />
            </div>
        </div>


        <div class="flex flex-col gap-3">
            <x-buttons.primary type="submit" class="w-full justify-center">{{ __('dashboard.common.save') }}</x-buttons.primary>
            <a href="{{ route('dashboard.governance-documents.index') }}" class="w-full">
                <x-buttons.secondary type="button" class="w-full justify-center">{{ __('dashboard.common.cancel') }}</x-buttons.secondary>
            </a>
        </div>

    </div>

</div>
