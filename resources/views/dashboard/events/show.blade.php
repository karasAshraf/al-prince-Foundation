@extends('layouts.app')

@section('title', __('dashboard.events.show') . ': ' . $event->title_ar)

@php
    $breadcrumbs = [
        ['label' => __('dashboard.events.title'), 'url' => route('dashboard.events.index')],
        ['label' => $event->title_ar, 'url' => null],
    ];
@endphp

@section('content')

    <div class="mb-6 flex items-center justify-between">
        <h1 class="text-xl font-bold text-[#3D342A]">{{ $event->title_ar }}</h1>
        <div class="flex items-center gap-3">
            <a href="{{ route('dashboard.events.edit', $event) }}">
                <x-buttons.primary>{{ __('dashboard.common.edit') }}</x-buttons.primary>
            </a>
            <a href="{{ route('dashboard.events.index') }}">
                <x-buttons.secondary>← {{ __('dashboard.common.back') }}</x-buttons.secondary>
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        <div class="space-y-5 lg:col-span-2">
            <div class="rounded-xl border border-[#B49C6E]/20 bg-[#EAEAE9] p-5 space-y-4">
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <p class="text-xs font-semibold uppercase text-[#3D342A]/50">{{ __('dashboard.events.event_title') }} (AR)</p>
                        <p class="mt-1 text-sm text-[#3D342A]">{{ $event->title_ar ?: '—' }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold uppercase text-[#3D342A]/50">{{ __('dashboard.events.event_title') }} (EN)</p>
                        <p class="mt-1 text-sm text-[#3D342A]">{{ $event->title_en ?: '—' }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold uppercase text-[#3D342A]/50">Slug</p>
                        <p class="mt-1 text-sm font-mono text-[#3D342A]/70" dir="ltr">{{ $event->slug ?: '—' }}</p>
                    </div>
                </div>

                @if($event->description_ar || $event->description_en)
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 border-t border-[#B49C6E]/20 pt-4">
                        <div>
                            <p class="text-xs font-semibold uppercase text-[#3D342A]/50">{{ __('dashboard.events.description') }} (AR)</p>
                            <p class="mt-1 text-sm text-[#3D342A]">{{ $event->description_ar ?: '—' }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-semibold uppercase text-[#3D342A]/50">{{ __('dashboard.events.description') }} (EN)</p>
                            <p class="mt-1 text-sm text-[#3D342A]">{{ $event->description_en ?: '—' }}</p>
                        </div>
                    </div>
                @endif
            </div>

            {{-- Gallery --}}
            @php $galleryItems = $event->getMedia('gallery'); @endphp
            @if($galleryItems->count() > 0)
                <div class="rounded-xl border border-[#B49C6E]/20 bg-[#EAEAE9] p-5">
                    <h3 class="mb-3 text-sm font-semibold text-[#3D342A]">{{ __('dashboard.events.gallery') }}</h3>
                    <div class="grid grid-cols-3 gap-3 sm:grid-cols-4 lg:grid-cols-6">
                        @foreach($galleryItems as $img)
                            <img
                                src="{{ $img->hasGeneratedConversion('gallery_thumb') ? $img->getUrl('gallery_thumb') : $img->getUrl() }}"
                                alt="{{ $img->name }}"
                                class="h-20 w-full rounded-lg object-cover border border-[#B49C6E]/20"
                            >
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <div class="space-y-5">
            <div class="rounded-xl border border-[#B49C6E]/20 bg-[#EAEAE9] p-5 space-y-3">
                <h3 class="text-sm font-semibold text-[#3D342A]">{{ __('dashboard.common.details') }}</h3>
                <div class="flex items-center justify-between">
                    <span class="text-xs text-[#3D342A]/60">{{ __('dashboard.common.status') }}</span>
                    <span @class(['inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium', 'bg-[#B49C6E]/20 text-[#A38B54]' => $event->is_active, 'bg-[#3D342A]/10 text-[#3D342A]/50' => !$event->is_active])>
                        {{ $event->is_active ? __('dashboard.common.active') : __('dashboard.common.inactive') }}
                    </span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-xs text-[#3D342A]/60">{{ __('dashboard.common.order') }}</span>
                    <span class="text-sm text-[#3D342A]">{{ $event->order }}</span>
                </div>
            </div>

            @php $featuredImage = \App\Helpers\MediaHelper::url($event, 'featured_image', 'image'); @endphp
            @if($featuredImage)
                <div class="rounded-xl border border-[#B49C6E]/20 bg-[#EAEAE9] p-5">
                    <h3 class="mb-3 text-sm font-semibold text-[#3D342A]">{{ __('dashboard.events.featured_image') }}</h3>
                    <img src="{{ $featuredImage }}" alt="{{ $event->title_ar }}" class="w-full rounded-lg object-cover border border-[#B49C6E]/20">
                </div>
            @endif
        </div>
    </div>

@endsection
