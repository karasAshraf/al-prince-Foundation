@extends('layouts.app')

@section('title', __('dashboard.hero_slides.list'))

@php
    $placements = \App\Helpers\NavigationHelper::getPlacements();
    $breadcrumbs = [['label' => __('dashboard.hero_slides.list'), 'url' => null]];
@endphp

@section('content')

    <div class="mb-5 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-xl font-bold text-[#3D342A]">{{ __('dashboard.hero_slides.title') }}</h1>
        </div>
        <a href="{{ route('dashboard.hero-slides.create') }}">
            <x-buttons.primary>{{ __('dashboard.hero_slides.add_new_slide') }}</x-buttons.primary>
        </a>
    </div>

    {{-- Filters --}}
    <div class="mb-6 rounded-xl border border-[#B49C6E]/20 bg-secondary p-4 shadow-sm">
        <form method="GET" action="{{ route('dashboard.hero-slides.index') }}" class="flex flex-wrap items-center gap-4">
            <div class="flex flex-col gap-1 min-w-[200px]">
                <label class="text-xs font-semibold text-[#3D342A]/70">{{ __('dashboard.hero_slides.filter_placement') }}</label>
                <select name="placement" onchange="this.form.submit()" class="rounded-lg border border-[#B49C6E]/40 bg-secondary px-3 py-1.5 text-xs text-[#3D342A] focus:border-[#A38B54] focus:outline-none">
                    <option value="">{{ __('dashboard.hero_slides.all_pages') }}</option>
                    @foreach($placements as $key => $label)
                        <option value="{{ $key }}" {{ request('placement') === $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            @if(request('placement'))
                <a href="{{ route('dashboard.hero-slides.index') }}" class="text-xs text-red-600 hover:underline mt-5">{{ __('dashboard.hero_slides.cancel_filter') }}</a>
            @endif
        </form>
    </div>

    {{-- Table --}}
    @if($slides->isEmpty())
        <x-tables.empty-state
            :title="__('dashboard.hero_slides.no_slides')"
            :message="__('dashboard.hero_slides.slides_desc')"
            :action-label="__('dashboard.hero_slides.add_slide')"
            :action-url="route('dashboard.hero-slides.create')"
        />
    @else
        <x-tables.table :headers="[__('dashboard.hero_slides.image'), __('dashboard.hero_slides.title_ar'), __('dashboard.hero_slides.placement'), __('dashboard.hero_slides.order'), __('dashboard.hero_slides.status'), '']">
            @foreach($slides as $item)
                <x-tables.table-row>
                    <td class="px-4 py-3">
                        <x-forms.image-preview :url="\App\Helpers\MediaHelper::url($item, 'hero_slide_images', 'image', 'thumb')" size="sm" />
                    </td>
                    <td class="px-4 py-3 font-medium text-[#3D342A]">
                        <a href="{{ route('dashboard.hero-slides.edit', $item) }}" class="hover:text-[#A38B54]">
                            {{ $item->title_ar }}
                        </a>
                        @if($item->title_en)
                            <span class="block text-xs text-[#3D342A]/50">{{ $item->title_en }}</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-xs font-semibold text-primary">
                        {{ $placements[$item->placement] ?? $item->placement }}
                    </td>
                    <td class="px-4 py-3 text-xs">
                        {{ $item->order }}
                    </td>
                    <td class="px-4 py-3 text-sm">
                        <x-tables.status-toggle
                            :id="$item->id"
                            :is-active="$item->is_active"
                            :route="route('dashboard.hero-slides.toggle-status', $item)"
                            :active-label="__('dashboard.common.active') ?: 'نشط'"
                            :inactive-label="__('dashboard.common.inactive') ?: 'غير نشط'"
                        />
                    </td>
                    <td class="px-4 py-3">
                        <x-tables.table-actions
                            :edit-url="route('dashboard.hero-slides.edit', $item)"
                            :delete-action="route('dashboard.hero-slides.destroy', $item)"
                            :item-label="$item->title_ar"
                        />
                    </td>
                </x-tables.table-row>
            @endforeach
        </x-tables.table>

        <x-tables.pagination :paginator="$slides" />
    @endif

    <x-modals.delete-modal />

@endsection
