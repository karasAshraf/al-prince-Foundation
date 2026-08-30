<div class="mb-4 flex flex-col gap-3 sm:flex-row sm:items-center">
    <x-tables.search placeholder="ابحث عن خبر..." />
    <x-tables.filters
        name="status"
        label="كل الحالات"
        :options="['draft' => 'مسودة', 'published' => 'منشور']"
    />
</div>