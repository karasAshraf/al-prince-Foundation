@if($members->isEmpty())
    <x-tables.empty-state
        title="{{ __('dashboard.team_members.no_members') }}"
        message="{{ __('dashboard.common.empty_state') }}"
        action-label="+ {{ __('dashboard.team_members.create') }}"
        :action-url="route('dashboard.team-members.create')"
    />
@else
    <x-tables.table :headers="[__('dashboard.common.image'), __('dashboard.team_members.name'), __('dashboard.team_members.job_title'), __('dashboard.common.type'), __('dashboard.common.order'), __('dashboard.common.status'), '']">
        @foreach($members as $item)
            <x-tables.table-row>
                <td class="px-4 py-3">
                    <x-forms.image-preview
                        :url="\App\Helpers\MediaHelper::url($item, 'team_photos', 'image')"
                        size="sm"
                    />
                </td>

                <td class="px-4 py-3 font-medium text-[#3D342A]">
                    <a href="{{ route('dashboard.team-members.show', $item) }}" class="hover:text-[#A38B54]">
                        {{ $item->name_ar }}
                    </a>
                    @if($item->name_en)
                        <span class="block text-xs text-[#3D342A]/50">{{ $item->name_en }}</span>
                    @endif
                </td>
                <td class="px-4 py-3 text-sm text-[#3D342A]/80">
                    {{ $item->position_ar }}
                </td>
                <td class="px-4 py-3 text-sm">
                    <span class="rounded-full bg-[#EAEAE9]/60 px-2.5 py-1 text-xs font-medium text-[#3D342A]">
                        {{ $item->type === 'board' ? __('dashboard.team_members.type_board') : __('dashboard.team_members.type_executive') }}
                    </span>
                </td>
                <td class="px-4 py-3 text-sm text-[#3D342A]/60">
                    {{ $item->order }}
                </td>
                <td class="px-4 py-3 text-sm">
                    <x-tables.status-toggle
                        :id="$item->id"
                        :is-active="$item->is_active"
                        :route="route('dashboard.team-members.toggle-status', $item)"
                    />
                </td>
                <td class="px-4 py-3">
                    <x-tables.table-actions
                        :show-url="route('dashboard.team-members.show', $item)"
                        :edit-url="route('dashboard.team-members.edit', $item)"
                        :delete-action="route('dashboard.team-members.destroy', $item)"
                        :item-label="$item->name_ar"
                    />
                </td>
            </x-tables.table-row>
        @endforeach
    </x-tables.table>

    <x-tables.pagination :paginator="$members" />
@endif
