<div class="mb-4 flex flex-col gap-3 sm:flex-row sm:items-center">
    <x-tables.search placeholder="{{ __('dashboard.common.search_placeholder') }}" />
    <x-tables.filters
        name="type"
        label="{{ __('dashboard.common.all') }}"
        :options="[
            'hero_slider' => __('dashboard.home_sections.section_title'),
            'home_section' => __('dashboard.home_sections.single'),
            'service_section' => __('dashboard.services.title'),
            'counters' => __('dashboard.common.details'),
            'latest_news' => __('dashboard.news.title')
        ]"

    />
</div>
