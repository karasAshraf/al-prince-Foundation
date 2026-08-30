@if($solutions->isEmpty())
    <x-tables.empty-state
        title="{{ __('dashboard.solutions.no_solutions') }}"
        message="{{ __('dashboard.common.empty_state') }}"
        action-label="+ {{ __('dashboard.solutions.create') }}"
        :action-url="route('dashboard.solutions.create')"
    />
@else
    <x-tables.table :headers="[__('dashboard.common.image'), __('dashboard.solutions.solution_title'), __('dashboard.news.slug'), __('dashboard.common.order'), __('dashboard.common.status'), '']">
        @foreach($solutions as $solution)
            <x-tables.table-row>
                <td class="px-4 py-3">
                    <x-forms.image-preview
                        :url="\App\Helpers\MediaHelper::url($solution, 'solution_images', 'image')"
                        size="sm"
                    />
                </td>
                <td class="px-4 py-3 text-sm font-medium text-[#3D342A]">
                    <a href="{{ route('dashboard.solutions.show', $solution) }}" class="hover:text-[#A38B54]">
                        {{ $solution->title_ar }}
                    </a>
                    @if($solution->title_en)
                        <p class="mt-0.5 text-xs text-[#3D342A]/50">{{ $solution->title_en }}</p>
                    @endif
                </td>
                <td class="px-4 py-3 text-sm font-mono text-[#3D342A]/70" dir="ltr">{{ $solution->slug ?: '—' }}</td>
                <td class="px-4 py-3 text-sm text-[#3D342A]">{{ $solution->order }}</td>
                <td class="px-4 py-3 text-sm">
                    <x-tables.status-toggle
                        :id="$solution->id"
                        :is-active="$solution->is_active"
                        :route="route('dashboard.solutions.toggle-status', $solution)"
                    />
                </td>
                <td class="px-4 py-3 text-end">
                    <x-tables.table-actions
                        :show-url="route('dashboard.solutions.show', $solution)"
                        :edit-url="route('dashboard.solutions.edit', $solution)"
                        :delete-action="route('dashboard.solutions.destroy', $solution)"
                        :item-label="$solution->title_ar"
                    />
                </td>
            </x-tables.table-row>
        @endforeach
    </x-tables.table>

    <x-tables.pagination :paginator="$solutions" />
@endif
