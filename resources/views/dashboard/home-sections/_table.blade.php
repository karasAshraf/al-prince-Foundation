@if($sections->isEmpty())
    <x-tables.empty-state
        title="{{ __('dashboard.home_sections.no_sections') }}"
        message="{{ __('dashboard.common.empty_state') }}"
        action-label="+ {{ __('dashboard.home_sections.create') }}"
        :action-url="route('dashboard.home-sections.create')"
    />
@else
    <x-tables.table :headers="[__('dashboard.common.type'), __('dashboard.home_sections.section_title'), __('dashboard.home_sections.subtitle'), __('dashboard.common.order'), __('dashboard.common.status'), '']">
        @foreach($sections as $item)
            <x-tables.table-row>
                @php
                    $typeLabels = [
                        'slider' => 'سلايدر (Slider)',
                        'hero_slider' => 'سلايدر هيرو الرئيسي (Hero Slider)',
                        'about_preview' => 'قسم نبذة عنا (About Preview)',
                        'service_section' => 'قسم الخدمات (Services Preview)',
                        'projects_preview' => 'قسم المشاريع (Projects Preview)',
                        'counters' => 'قسم العدادات (Counters)',
                        'latest_news' => 'قسم أحدث الأخبار (Latest News)',
                        'cta' => 'قسم اتخاذ إجراء (CTA)',
                        'home_section' => 'قسم رئيسي عام (Home Section)',
                    ];
                @endphp
                <td class="px-4 py-3 text-sm">
                    <span class="rounded-full bg-[#EAEAE9]/60 px-2.5 py-1 text-xs font-medium text-[#3D342A]">
                        {{ $typeLabels[$item->type] ?? $item->type }}
                    </span>
                </td>
                <td class="px-4 py-3 font-medium text-[#3D342A]">
                    <a href="{{ route('dashboard.home-sections.show', $item) }}" class="hover:text-[#A38B54]">
                        {{ $item->title_ar }}
                    </a>
                    @if($item->title_en)
                        <span class="block text-xs text-[#3D342A]/50">{{ $item->title_en }}</span>
                    @endif
                </td>
                <td class="px-4 py-3 text-sm text-[#3D342A]/70">
                    {{ $item->label_ar ?: '—' }}
                    @if($item->label_en)
                        <span class="block text-xs text-[#3D342A]/50">{{ $item->label_en }}</span>
                    @endif
                </td>
                <td class="px-4 py-3 text-sm text-[#3D342A]/60">
                    {{ $item->order }}
                </td>
                <td class="px-4 py-3 text-sm">
                    <x-tables.status-toggle
                        :id="$item->id"
                        :is-active="$item->is_active"
                        :route="route('dashboard.home-sections.toggle-status', $item)"
                    />
                </td>
                <td class="px-4 py-3">
                    <x-tables.table-actions
                        :show-url="route('dashboard.home-sections.show', $item)"
                        :edit-url="route('dashboard.home-sections.edit', $item)"
                        :delete-action="route('dashboard.home-sections.destroy', $item)"
                        :item-label="$item->title_ar"
                    />
                </td>
            </x-tables.table-row>
        @endforeach
    </x-tables.table>

    <x-tables.pagination :paginator="$sections" />
@endif
