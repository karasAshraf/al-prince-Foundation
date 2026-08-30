<div class="mb-4 flex flex-col gap-3 sm:flex-row sm:items-center">
    <x-tables.search placeholder="{{ __('dashboard.common.search_placeholder') }}" />
    <x-tables.filters
        name="status"
        label="{{ __('dashboard.common.all') }}"
        :options="['draft' => __('dashboard.news.draft'), 'published' => __('dashboard.news.published')]"
    />
</div>
