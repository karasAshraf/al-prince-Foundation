@if($partners->isEmpty())
    <x-tables.empty-state
        title="{{ __('dashboard.partners.no_partners') }}"
        message="{{ __('dashboard.common.empty_state') }}"
        action-label="+ {{ __('dashboard.partners.create') }}"
        :action-url="route('dashboard.partners.create')"
    />
@else
    <x-tables.table :headers="[__('dashboard.common.image'), __('dashboard.partners.partner_name'), __('dashboard.common.order'), __('dashboard.common.status'), '']">
        @foreach($partners as $partner)
            <x-tables.table-row>
                <td class="px-4 py-3">
                    <x-forms.image-preview
                        :url="\App\Helpers\MediaHelper::url($partner, 'partner_logos', 'image')
                              ?? (\App\Helpers\MediaHelper::resolveUrl($partner->external_link))"
                        size="sm"
                    />
                </td>
                <td class="px-4 py-3 text-sm font-medium text-[#3D342A]">
                    <a href="{{ route('dashboard.partners.show', $partner) }}" class="hover:text-[#A38B54]">
                        {{ $partner->name_ar }}
                    </a>
                    @if($partner->name_en)
                        <p class="mt-0.5 text-xs text-[#3D342A]/50">{{ $partner->name_en }}</p>
                    @endif
                </td>
                <td class="px-4 py-3 text-sm text-[#3D342A]">{{ $partner->order }}</td>
                <td class="px-4 py-3 text-sm">
                    <x-tables.status-toggle
                        :id="$partner->id"
                        :is-active="$partner->is_active"
                        :route="route('dashboard.partners.toggle-status', $partner)"
                    />
                </td>
                <td class="px-4 py-3 text-end">
                    <x-tables.table-actions
                        :show-url="route('dashboard.partners.show', $partner)"
                        :edit-url="route('dashboard.partners.edit', $partner)"
                        :delete-action="route('dashboard.partners.destroy', $partner)"
                        :item-label="$partner->name_ar"
                    />
                </td>
            </x-tables.table-row>
        @endforeach
    </x-tables.table>

    <x-tables.pagination :paginator="$partners" />
@endif
