@if($programs->isEmpty())
    <x-tables.empty-state
        title="{{ __('dashboard.programs.no_programs') }}"
        message="{{ __('dashboard.common.empty_state') }}"
        action-label="+ {{ __('dashboard.programs.create') }}"
        :action-url="route('dashboard.programs.create')"
    />
@else
    <x-tables.table :headers="[__('dashboard.common.image'), __('dashboard.programs.single'), __('dashboard.projects.title'), __('dashboard.common.order'), __('dashboard.common.status'), '']">
        @foreach($programs as $program)
            <x-tables.table-row>
                <td class="px-4 py-3">
                    <x-forms.image-preview
                        :url="\App\Helpers\MediaHelper::url($program, 'program_images', 'image', 'thumb')
                              ?? (\App\Helpers\MediaHelper::resolveUrl($program->external_link ?? null))"
                        size="sm"
                    />
                </td>

                <td class="px-4 py-3">
                    <a
                        href="{{ route('dashboard.programs.show', $program) }}"
                        class="text-sm font-medium text-[#3D342A] hover:text-[#A38B54]"
                    >
                        {{ $program->title_ar }}
                    </a>
                    @if($program->title_en)
                        <p class="mt-0.5 text-xs text-[#3D342A]/50">{{ $program->title_en }}</p>
                    @endif
                </td>
                <td class="px-4 py-3 text-sm text-[#3D342A]/60">
                    {{ $program->projects_count }}
                </td>
                <td class="px-4 py-3 text-sm text-[#3D342A]/60">
                    {{ $program->order ?? '—' }}
                </td>
                <td class="px-4 py-3 text-sm">
                    <x-tables.status-toggle
                        :id="$program->id"
                        :is-active="$program->is_active"
                        :route="route('dashboard.programs.toggle-status', $program)"
                    />
                </td>
                <td class="px-4 py-3">
                    <x-tables.table-actions
                        :show-url="route('dashboard.programs.show', $program)"
                        :edit-url="route('dashboard.programs.edit', $program)"
                        :delete-action="route('dashboard.programs.destroy', $program)"
                        :item-label="$program->title_ar"
                    />
                </td>
            </x-tables.table-row>
        @endforeach
    </x-tables.table>

    <x-tables.pagination :paginator="$programs" />
@endif
