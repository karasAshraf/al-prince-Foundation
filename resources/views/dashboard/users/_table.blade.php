@if($users->isEmpty())
    <x-tables.empty-state
        title="{{ __('dashboard.users.no_users') }}"
        message="{{ __('dashboard.common.empty_state') }}"
        action-label="+ {{ __('dashboard.users.create') }}"
        :action-url="route('dashboard.users.create')"
    />
@else
    <x-tables.table :headers="[__('dashboard.users.name'), __('dashboard.users.email'), __('dashboard.common.created_at'), __('dashboard.common.actions')]">
        @foreach($users as $u)
            <x-tables.table-row>
                <td class="px-4 py-3 text-sm font-medium text-[#3D342A]">{{ $u->name }}</td>
                <td class="px-4 py-3 text-sm text-[#3D342A]/80">{{ $u->email }}</td>
                <td class="px-4 py-3 text-sm text-[#3D342A]/60">{{ $u->created_at->format('Y-m-d') }}</td>
                <td class="px-4 py-3 text-end">
                    <x-tables.table-actions
                        :edit-url="route('dashboard.users.edit', $u)"
                        :delete-action="route('dashboard.users.destroy', $u)"
                        :item-label="$u->name"
                    />
                </td>
            </x-tables.table-row>
        @endforeach
    </x-tables.table>

    <x-tables.pagination :paginator="$users" />
@endif
