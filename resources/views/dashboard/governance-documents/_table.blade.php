@if($documents->isEmpty())
    <x-tables.empty-state
        title="{{ __('dashboard.governance_documents.no_documents') }}"
        message="{{ __('dashboard.common.empty_state') }}"
        action-label="+ {{ __('dashboard.governance_documents.create') }}"
        :action-url="route('dashboard.governance-documents.create')"
    />
@else
    <x-tables.table :headers="[__('dashboard.governance_documents.single'), __('dashboard.governance_documents.category'), __('dashboard.governance_documents.fiscal_year'), __('dashboard.common.file'), __('dashboard.common.status'), __('dashboard.common.order'), '']">
        @foreach($documents as $item)
            <x-tables.table-row>
                <td class="px-4 py-3 font-medium text-[#3D342A]">
                    <a href="{{ route('dashboard.governance-documents.show', $item) }}" class="hover:text-[#A38B54] flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-600 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                        </svg>
                        <div>
                            <span>{{ $item->title_ar }}</span>
                            @if($item->title_en)
                                <span class="block text-xs text-[#3D342A]/50">{{ $item->title_en }}</span>
                            @endif
                        </div>
                    </a>
                </td>
                <td class="px-4 py-3 text-sm">
                    <span class="rounded-full bg-secondary/60 px-2.5 py-1 text-xs font-medium text-[#3D342A]">
                        @if($item->category === 'policies') {{ __('dashboard.governance_documents.categories.policies') }}
                        @elseif($item->category === 'financial_reports') {{ __('dashboard.governance_documents.categories.financial_reports') }}
                        @else {{ __('dashboard.governance_documents.categories.achievement_reports') }} @endif
                    </span>
                </td>
                <td class="px-4 py-3 text-sm text-[#3D342A]/80 font-medium">
                    {{ $item->fiscal_year }}
                </td>
                <td class="px-4 py-3 text-sm text-[#3D342A]/60">
                    {{ $item->file_size ? number_format($item->file_size / 1024, 1) . ' KB' : '—' }}
                </td>
                <td class="px-4 py-3 text-sm">
                    <x-tables.status-toggle
                        :id="$item->id"
                        :is-active="$item->is_active"
                        :route="route('dashboard.governance-documents.toggle-status', $item)"
                    />
                </td>
                <td class="px-4 py-3 text-sm text-[#3D342A]/60">
                    {{ $item->order }}
                </td>
                <td class="px-4 py-3">
                    <x-tables.table-actions
                        :show-url="route('dashboard.governance-documents.show', $item)"
                        :edit-url="route('dashboard.governance-documents.edit', $item)"
                        :delete-action="route('dashboard.governance-documents.destroy', $item)"
                        :item-label="$item->title_ar"
                    />
                </td>
            </x-tables.table-row>
        @endforeach
    </x-tables.table>

    <x-tables.pagination :paginator="$documents" />
@endif
