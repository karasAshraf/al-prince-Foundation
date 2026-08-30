<div class="mb-4 flex flex-col gap-3 sm:flex-row sm:items-center">
    <x-tables.search placeholder="{{ __('dashboard.common.search_placeholder') }}" />
    <x-tables.filters
        name="is_active"
        label="{{ __('dashboard.common.status') }}"
        :options="['1' => __('dashboard.common.active'), '0' => __('dashboard.common.inactive')]"
    />
</div>
