@extends('layouts.app')

@section('title', __('dashboard.media_library.title'))

@php
    $breadcrumbs = [['label' => __('dashboard.media_library.title'), 'url' => null]];
@endphp

@section('content')
    <div class="mb-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <h1 class="text-xl font-bold text-[#3D342A]">{{ __('dashboard.media_library.title') }}</h1>
        <a href="{{ route('dashboard.media-library.create') }}">
            <x-buttons.primary>+ {{ __('dashboard.media_library.create') }}</x-buttons.primary>
        </a>
    </div>

    <x-alerts.success />
    <x-alerts.error />

    {{-- Filters Card --}}
    <div class="mb-6 rounded-xl border border-[#B49C6E]/20 bg-[#EAEAE9] p-4 shadow-sm">
        <form method="GET" action="{{ route('dashboard.media-library.index') }}" class="grid grid-cols-1 gap-4 sm:grid-cols-3">
            <x-forms.input
                name="search"
                label="{{ __('dashboard.media_library.search_placeholder') }}"
                placeholder="{{ __('dashboard.common.search_placeholder') }}"
                :value="request('search')"
            />

            <x-forms.select
                name="category"
                label="{{ __('dashboard.media_library.category') }}"
                :options="$categories"
                :selected="request('category')"
                placeholder="{{ __('dashboard.media_library.all_categories') }}"
            />

            <div class="flex items-end gap-3">
                <div class="flex-1">
                    <x-forms.select
                        name="is_active"
                        label="{{ __('dashboard.common.status') }}"
                        :options="['1' => __('dashboard.common.active'), '0' => __('dashboard.common.inactive')]"
                        :selected="request('is_active')"
                        placeholder="{{ __('dashboard.common.all') }}"
                    />
                </div>
                <div class="flex gap-2">
                    <x-buttons.primary type="submit" class="h-[44px]">{{ __('dashboard.common.filter') }}</x-buttons.primary>
                    @if(request()->anyFilled(['search', 'category', 'is_active']))
                        <a href="{{ route('dashboard.media-library.index') }}">
                            <x-buttons.secondary type="button" class="h-[44px]">{{ __('dashboard.common.cancel') }}</x-buttons.secondary>
                        </a>
                    @endif
                </div>
            </div>
        </form>
    </div>

    {{-- Table / Cards --}}
    @if($items->isEmpty())
        <x-tables.empty-state
            title="{{ __('dashboard.media_library.no_items') }}"
            message="{{ __('dashboard.media_library.empty_state') }}"
            action-label="+ {{ __('dashboard.media_library.create') }}"
            :action-url="route('dashboard.media-library.create')"
        />
    @else
        <x-tables.table :headers="[__('dashboard.common.title'), __('dashboard.media_library.category'), __('dashboard.media_library.type_file'), __('dashboard.common.order'), __('dashboard.common.status'), '']">
            @foreach($items as $item)
                <x-tables.table-row>
                    <td class="px-4 py-3 text-sm font-medium text-[#3D342A]">
                        <div class="font-bold text-[#3D342A]">{{ $item->title_ar }}</div>
                        @if($item->title_en)
                            <div class="mt-0.5 text-xs text-[#3D342A]/50">{{ $item->title_en }}</div>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-sm text-[#3D342A]">
                        {{ $categories[$item->category] ?? $item->category ?? '—' }}
                    </td>
                    <td class="px-4 py-3 text-sm space-y-1">
                        @php
                            $mediaFiles = $item->getMedia('media_library_files');
                            $links = [];
                            if ($item->external_link) {
                                $decoded = json_decode($item->external_link, true);
                                $links = is_array($decoded) ? $decoded : [$item->external_link];
                            }
                        @endphp
                        @if($mediaFiles->count() > 0)
                            @foreach($mediaFiles as $mediaFile)
                                <a href="{{ $mediaFile->getUrl() }}" target="_blank" class="text-[#A38B54] hover:underline flex items-center gap-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    <span class="max-w-[150px] truncate">{{ $mediaFile->file_name }}</span>
                                </a>
                            @endforeach
                        @endif
                        @if(count($links) > 0)
                            @foreach($links as $link)
                                <a href="{{ $link }}" target="_blank" class="text-blue-600 hover:underline flex items-center gap-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                    </svg>
                                    <span class="max-w-[150px] truncate">{{ $link }}</span>
                                </a>
                            @endforeach
                        @endif
                        @if($mediaFiles->isEmpty() && empty($links))
                            <span class="text-[#3D342A]/50">—</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-sm text-[#3D342A]">{{ $item->order }}</td>
                    <td class="px-4 py-3 text-sm">
                        <x-tables.status-toggle
                            :id="$item->id"
                            :is-active="$item->is_active"
                            :route="route('dashboard.media-library.toggle-status', $item)"
                        />
                    </td>
                    <td class="px-4 py-3 text-end">
                        <x-tables.table-actions
                            :edit-url="route('dashboard.media-library.edit', $item)"
                            :delete-action="route('dashboard.media-library.destroy', $item)"
                            :item-label="$item->title_ar"
                        />
                    </td>
                </x-tables.table-row>
            @endforeach
        </x-tables.table>

        <x-tables.pagination :paginator="$items" />
    @endif

    <x-modals.delete-modal />
@endsection
