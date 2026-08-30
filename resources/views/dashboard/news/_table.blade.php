@if($news->isEmpty())
    <x-tables.empty-state
        title="{{ __('dashboard.news.no_news') }}"
        message="{{ __('dashboard.common.empty_state') }}"
        action-label="+ {{ __('dashboard.news.create') }}"
        :action-url="route('dashboard.news.create')"
    />
@else
    <x-tables.table :headers="[__('dashboard.common.image'), __('dashboard.common.title'), __('dashboard.common.status'), __('dashboard.news.published_at'), '']">
        @foreach($news as $item)
            <x-tables.table-row>
                <td class="px-4 py-3">
                    <x-forms.image-preview
                        :url="\App\Helpers\MediaHelper::url($item, 'news_images', 'image', 'thumb')
                              ?? (\App\Helpers\MediaHelper::resolveUrl($item->external_link))"
                        size="sm"
                    />
                </td>
                <td class="px-4 py-3">
                    <a href="{{ route('dashboard.news.show', $item) }}" class="text-sm font-medium text-[#3D342A] hover:text-[#A38B54]">
                        {{ $item->title_ar }}
                    </a>
                </td>
                <td class="px-4 py-3 text-sm">
                    <x-tables.status-toggle
                        :id="$item->id"
                        :is-active="$item->is_active"
                        :route="route('dashboard.news.toggle-status', $item)"
                        :active-label="__('dashboard.news.published')"
                        :inactive-label="__('dashboard.news.draft')"
                    />
                </td>
                <td class="px-4 py-3 text-sm text-[#3D342A]/60">
                    {{ $item->published_at?->format('Y-m-d') ?? '—' }}
                </td>
                <td class="px-4 py-3">
                    <x-tables.table-actions
                        :show-url="route('dashboard.news.show', $item)"
                        :edit-url="route('dashboard.news.edit', $item)"
                        :delete-action="route('dashboard.news.destroy', $item)"
                        :item-label="$item->title_ar"
                    />
                </td>
            </x-tables.table-row>
        @endforeach
    </x-tables.table>

    <x-tables.pagination :paginator="$news" />
@endif