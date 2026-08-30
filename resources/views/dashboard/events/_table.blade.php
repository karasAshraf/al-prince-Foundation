@if($events->isEmpty())
    <x-tables.empty-state
        title="{{ __('dashboard.events.no_events') }}"
        message="{{ __('dashboard.common.empty_state') }}"
        action-label="+ {{ __('dashboard.events.create') }}"
        :action-url="route('dashboard.events.create')"
    />
@else
    <x-tables.table :headers="[__('dashboard.common.image'), __('dashboard.events.event_title'), __('dashboard.news.slug'), __('dashboard.common.order'), __('dashboard.common.status'), '']">
        @foreach($events as $event)
            <x-tables.table-row>
                <td class="px-4 py-3">
                    <x-forms.image-preview
                        :url="\App\Helpers\MediaHelper::url($event, 'featured_image', 'image')"
                        size="sm"
                    />
                </td>
                <td class="px-4 py-3 text-sm font-medium text-[#3D342A]">
                    <a href="{{ route('dashboard.events.show', $event) }}" class="hover:text-[#A38B54]">
                        {{ $event->title_ar }}
                    </a>
                    @if($event->title_en)
                        <p class="mt-0.5 text-xs text-[#3D342A]/50">{{ $event->title_en }}</p>
                    @endif
                </td>
                <td class="px-4 py-3 text-sm font-mono text-[#3D342A]/70" dir="ltr">{{ $event->slug ?: '—' }}</td>
                <td class="px-4 py-3 text-sm text-[#3D342A]">{{ $event->order }}</td>
                <td class="px-4 py-3 text-sm">
                    <x-tables.status-toggle
                        :id="$event->id"
                        :is-active="$event->is_active"
                        :route="route('dashboard.events.toggle-status', $event)"
                    />
                </td>
                <td class="px-4 py-3 text-end">
                    <x-tables.table-actions
                        :show-url="route('dashboard.events.show', $event)"
                        :edit-url="route('dashboard.events.edit', $event)"
                        :delete-action="route('dashboard.events.destroy', $event)"
                        :item-label="$event->title_ar"
                    />
                </td>
            </x-tables.table-row>
        @endforeach
    </x-tables.table>

    <x-tables.pagination :paginator="$events" />
@endif
