<div class="mb-4 flex flex-col gap-3 sm:flex-row sm:items-center">
    <x-tables.search placeholder="{{ __('dashboard.common.search_placeholder') }}" />
    <x-tables.filters
        name="category"
        label="{{ __('dashboard.common.all') }}"
        :options="[
            'policies' => __('dashboard.governance_documents.categories.policies'),
            'financial_reports' => __('dashboard.governance_documents.categories.financial_reports'),
            'achievement_reports' => __('dashboard.governance_documents.categories.achievement_reports')
        ]"
    />
</div>
