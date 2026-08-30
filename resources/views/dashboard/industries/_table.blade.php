@if($industries->isEmpty())
    <x-tables.empty-state
        title="{{ __('dashboard.industries.no_industries') }}"
        message="{{ __('dashboard.common.empty_state') }}"
        action-label="+ {{ __('dashboard.industries.create') }}"
        :action-url="route('dashboard.industries.create')"
    />
@else
    <x-tables.table :headers="[__('dashboard.common.image'), __('dashboard.industries.industry_title'), __('dashboard.news.slug'), __('dashboard.common.order'), __('dashboard.common.status'), '']">
        @foreach($industries as $industry)
            <x-tables.table-row>
                <td class="px-4 py-3">
                    <x-forms.image-preview
                        :url="\App\Helpers\MediaHelper::url($industry, 'industry_images', 'image')"
                        size="sm"
                    />
                </td>
                <td class="px-4 py-3 text-sm font-medium text-[#3D342A]">
                    <a href="{{ route('dashboard.industries.show', $industry) }}" class="hover:text-[#A38B54]">
                        {{ $industry->title_ar }}
                    </a>
                    @if($industry->title_en)
                        <p class="mt-0.5 text-xs text-[#3D342A]/50">{{ $industry->title_en }}</p>
                    @endif
                </td>
                <td class="px-4 py-3 text-sm font-mono text-[#3D342A]/70" dir="ltr">{{ $industry->slug ?: '—' }}</td>
                <td class="px-4 py-3 text-sm text-[#3D342A]">{{ $industry->order }}</td>
                <td class="px-4 py-3 text-sm">
                    <x-tables.status-toggle
                        :id="$industry->id"
                        :is-active="$industry->is_active"
                        :route="route('dashboard.industries.toggle-status', $industry)"
                    />
                </td>
                <td class="px-4 py-3 text-end">
                    <x-tables.table-actions
                        :show-url="route('dashboard.industries.show', $industry)"
                        :edit-url="route('dashboard.industries.edit', $industry)"
                        :delete-action="route('dashboard.industries.destroy', $industry)"
                        :item-label="$industry->title_ar"
                    />
                </td>
            </x-tables.table-row>
        @endforeach
    </x-tables.table>

    <x-tables.pagination :paginator="$industries" />
@endif
