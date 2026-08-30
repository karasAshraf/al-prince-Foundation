@if($services->isEmpty())
    <x-tables.empty-state
        title="{{ __('dashboard.services.no_services') }}"
        message="{{ __('dashboard.common.empty_state') }}"
        action-label="+ {{ __('dashboard.services.create') }}"
        :action-url="route('dashboard.services.create')"
    />
@else
    <x-tables.table :headers="[__('dashboard.common.image'), __('dashboard.services.service_title'), __('dashboard.news.slug'), __('dashboard.common.order'), __('dashboard.common.status'), '']">
        @foreach($services as $service)
            <x-tables.table-row>
                <td class="px-4 py-3">
                    <x-forms.image-preview
                        :url="\App\Helpers\MediaHelper::url($service, 'service_images', 'image')
                              ?? (\App\Helpers\MediaHelper::resolveUrl($service->external_link))"
                        size="sm"
                    />
                </td>
                <td class="px-4 py-3 text-sm font-medium text-[#3D342A]">
                    <a href="{{ route('dashboard.services.show', $service) }}" class="hover:text-[#A38B54]">
                        {{ $service->title_ar }}
                    </a>
                    @if($service->title_en)
                        <p class="mt-0.5 text-xs text-[#3D342A]/50">{{ $service->title_en }}</p>
                    @endif
                </td>
                <td class="px-4 py-3 text-sm font-mono text-[#3D342A]/70" dir="ltr">{{ $service->slug ?: '—' }}</td>
                <td class="px-4 py-3 text-sm text-[#3D342A]">{{ $service->order }}</td>
                <td class="px-4 py-3 text-sm">
                    <x-tables.status-toggle
                        :id="$service->id"
                        :is-active="$service->is_active"
                        :route="route('dashboard.services.toggle-status', $service)"
                    />
                </td>
                <td class="px-4 py-3 text-end">
                    <x-tables.table-actions
                        :show-url="route('dashboard.services.show', $service)"
                        :edit-url="route('dashboard.services.edit', $service)"
                        :delete-action="route('dashboard.services.destroy', $service)"
                        :item-label="$service->title_ar"
                    />
                </td>
            </x-tables.table-row>
        @endforeach
    </x-tables.table>

    <x-tables.pagination :paginator="$services" />
@endif
