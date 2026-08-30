@if($activities->isEmpty())
    <x-tables.empty-state
        title="{{ __('dashboard.activities.no_activities') }}"
        message="{{ __('dashboard.common.empty_state') }}"
        action-label="+ {{ __('dashboard.activities.create') }}"
        :action-url="route('dashboard.activities.create')"
    />
@else
    <x-tables.table :headers="[__('dashboard.common.image'), __('dashboard.activities.activity_title'), __('dashboard.news.slug'), __('dashboard.common.order'), __('dashboard.common.status'), '']">
        @foreach($activities as $activity)
            <x-tables.table-row>
                <td class="px-4 py-3">
                    <x-forms.image-preview
                        :url="\App\Helpers\MediaHelper::url($activity, 'featured_image', 'image')"
                        size="sm"
                    />
                </td>
                <td class="px-4 py-3 text-sm font-medium text-[#3D342A]">
                    <a href="{{ route('dashboard.activities.show', $activity) }}" class="hover:text-[#A38B54]">
                        {{ $activity->title_ar }}
                    </a>
                    @if($activity->title_en)
                        <p class="mt-0.5 text-xs text-[#3D342A]/50">{{ $activity->title_en }}</p>
                    @endif
                </td>
                <td class="px-4 py-3 text-sm font-mono text-[#3D342A]/70" dir="ltr">{{ $activity->slug ?: '—' }}</td>
                <td class="px-4 py-3 text-sm text-[#3D342A]">{{ $activity->order }}</td>
                <td class="px-4 py-3 text-sm">
                    <x-tables.status-toggle
                        :id="$activity->id"
                        :is-active="$activity->is_active"
                        :route="route('dashboard.activities.toggle-status', $activity)"
                    />
                </td>
                <td class="px-4 py-3 text-end">
                    <x-tables.table-actions
                        :show-url="route('dashboard.activities.show', $activity)"
                        :edit-url="route('dashboard.activities.edit', $activity)"
                        :delete-action="route('dashboard.activities.destroy', $activity)"
                        :item-label="$activity->title_ar"
                    />
                </td>
            </x-tables.table-row>
        @endforeach
    </x-tables.table>

    <x-tables.pagination :paginator="$activities" />
@endif
