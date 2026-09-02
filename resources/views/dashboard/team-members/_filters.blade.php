<div class="mb-5 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

    {{-- Type Tabs --}}
    <div class="flex items-center gap-1 rounded-xl border border-[#B49C6E]/30 bg-secondary p-1.5 shadow-sm">
        <a
            href="{{ route('dashboard.team-members.index', ['type' => 'board']) }}"
            @class([
                'rounded-lg px-4 py-2 text-sm font-medium transition',
                'bg-[#A38B54] text-background shadow-sm' => ($type ?? 'board') === 'board',
                'text-[#3D342A]/70 hover:bg-secondary/30' => ($type ?? 'board') !== 'board',
            ])
        >
            {{ __('dashboard.team_members.type_board') }}
        </a>
        <a
            href="{{ route('dashboard.team-members.index', ['type' => 'executive']) }}"
            @class([
                'rounded-lg px-4 py-2 text-sm font-medium transition',
                'bg-[#A38B54] text-background shadow-sm' => ($type ?? '') === 'executive',
                'text-[#3D342A]/70 hover:bg-secondary/30' => ($type ?? '') !== 'executive',
            ])
        >
            {{ __('dashboard.team_members.type_executive') }}
        </a>
    </div>

    {{-- Search Form --}}
    <div class="flex items-center gap-2">
        <x-tables.search placeholder="{{ __('dashboard.common.search_placeholder') }}" />
    </div>
</div>
