@if($projects->isEmpty())
    <x-tables.empty-state
        title="{{ __('dashboard.projects.no_projects') }}"
        message="{{ __('dashboard.common.empty_state') }}"
        action-label="+ {{ __('dashboard.projects.create') }}"
        :action-url="route('dashboard.projects.create')"
    />
@else
    <x-tables.table :headers="[__('dashboard.common.image'), __('dashboard.projects.single'), __('dashboard.programs.single'), __('dashboard.projects.status'), __('dashboard.common.status'), __('dashboard.common.created_at'), '']">
        @foreach($projects as $project)
            <x-tables.table-row>

                {{-- Thumbnail --}}
                <td class="px-4 py-3">
                    <x-forms.image-preview
                        :url="\App\Helpers\MediaHelper::url($project, 'project_images', 'image', 'thumb')
                              ?? (\App\Helpers\MediaHelper::resolveUrl($project->external_link))"
                        size="sm"
                    />
                </td>

                {{-- Title --}}
                <td class="px-4 py-3">
                    <a
                        href="{{ route('dashboard.projects.show', $project) }}"
                        class="text-sm font-medium text-[#3D342A] hover:text-[#A38B54]"
                    >
                        {{ $project->title_ar }}
                    </a>
                    @if($project->title_en)
                        <p class="mt-0.5 text-xs text-[#3D342A]/50">{{ $project->title_en }}</p>
                    @endif
                </td>

                {{-- Program --}}
                <td class="px-4 py-3 text-sm text-[#3D342A]/70">
                    @if($project->program)
                        <a
                            href="{{ route('dashboard.programs.show', $project->program) }}"
                            class="hover:text-[#A38B54]"
                        >
                            {{ $project->program->title_ar }}
                        </a>
                    @else
                        <span class="text-[#3D342A]/30">—</span>
                    @endif
                </td>

                {{-- Execution Status --}}
                <td class="px-4 py-3">
                    <span @class([
                        'rounded-full px-2.5 py-1 text-xs font-medium',
                        'bg-blue-100 text-blue-700'         => $project->project_status === 'ongoing',
                        'bg-[#B49C6E]/30 text-[#A38B54]'    => $project->project_status === 'completed',
                    ])>
                        {{ $project->project_status === 'ongoing' ? __('dashboard.projects.in_progress') : __('dashboard.projects.completed') }}
                    </span>
                </td>

                {{-- Publish Status --}}
                <td class="px-4 py-3 text-sm">
                    <x-tables.status-toggle
                        :id="$project->id"
                        :is-active="$project->is_active"
                        :route="route('dashboard.projects.toggle-status', $project)"
                        :active-label="__('dashboard.common.published')"
                        :inactive-label="__('dashboard.common.draft')"
                    />
                </td>

                {{-- Dates --}}
                <td class="px-4 py-3 text-xs text-[#3D342A]/60">
                    @if($project->start_date || $project->end_date)
                        <span>{{ $project->start_date?->format('Y-m-d') ?? '?' }}</span>
                        <span class="mx-1 text-[#3D342A]/30">←</span>
                        <span>{{ $project->end_date?->format('Y-m-d') ?? '—' }}</span>
                    @else
                        <span class="text-[#3D342A]/30">—</span>
                    @endif
                </td>

                {{-- Actions --}}
                <td class="px-4 py-3">
                    <x-tables.table-actions
                        :show-url="route('dashboard.projects.show', $project)"
                        :edit-url="route('dashboard.projects.edit', $project)"
                        :delete-action="route('dashboard.projects.destroy', $project)"
                        :item-label="$project->title_ar"
                    />
                </td>

            </x-tables.table-row>
        @endforeach
    </x-tables.table>

    <x-tables.pagination :paginator="$projects" />
@endif
