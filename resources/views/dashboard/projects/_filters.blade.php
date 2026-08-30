<div class="mb-4 flex flex-col gap-3 sm:flex-row sm:items-center">
    <x-tables.search placeholder="{{ __('dashboard.common.search_placeholder') }}" />

    {{-- Filter: Program --}}
    <x-tables.filters
        name="program_id"
        label="{{ __('dashboard.common.all') }}"
        :options="$programs->toArray()"
    />

    {{-- Filter: Execution Status --}}
    <x-tables.filters
        name="project_status"
        label="{{ __('dashboard.common.all') }}"
        :options="['ongoing' => __('dashboard.projects.in_progress'), 'completed' => __('dashboard.projects.completed')]"
    />
</div>
