@if($sections->isEmpty())
    <x-tables.empty-state
        title="{{ __('dashboard.about_sections.no_sections') }}"
        message="{{ __('dashboard.common.empty_state') }}"
        action-label="+ {{ __('dashboard.about_sections.create') }}"
        :action-url="route('dashboard.about-sections.create')"
    />
@else
    <x-tables.table :headers="[__('dashboard.common.image'), __('dashboard.about_sections.section_title'), __('dashboard.common.order'), __('dashboard.common.status'), __('dashboard.common.updated_at'), '']">
        @foreach($sections as $item)
            <x-tables.table-row>
                <td class="px-4 py-3">
                    <x-forms.image-preview :url="\App\Helpers\MediaHelper::url($item, 'about_images', 'image', 'thumb')" size="sm" />
                </td>
                <td class="px-4 py-3 font-medium text-[#3D342A]">
                    <a href="{{ route('dashboard.about-sections.show', $item) }}" class="hover:text-[#A38B54]">
                        {{ $item->title_ar }}
                    </a>
                    @if($item->title_en)
                        <span class="block text-xs text-[#3D342A]/50">{{ $item->title_en }}</span>
                    @endif
                </td>
                <td class="px-4 py-3 text-sm text-[#3D342A]/60">
                    {{ $item->order }}
                </td>
                <td class="px-4 py-3 text-sm">
                    <x-tables.status-toggle
                        :id="$item->id"
                        :is-active="$item->is_active"
                        :route="route('dashboard.about-sections.toggle-status', $item)"
                        :active-label="__('dashboard.common.published')"
                        :inactive-label="__('dashboard.common.draft')"
                    />
                </td>
                <td class="px-4 py-3 text-sm text-[#3D342A]/60">
                    {{ $item->updated_at?->format('Y-m-d H:i') }}
                </td>
                <td class="px-4 py-3">
                    <x-tables.table-actions
                        :show-url="route('dashboard.about-sections.show', $item)"
                        :edit-url="route('dashboard.about-sections.edit', $item)"
                        :delete-action="route('dashboard.about-sections.destroy', $item)"
                        :item-label="$item->title_ar"
                    />
                </td>
            </x-tables.table-row>
        @endforeach
    </x-tables.table>

    <x-tables.pagination :paginator="$sections" />
@endif
