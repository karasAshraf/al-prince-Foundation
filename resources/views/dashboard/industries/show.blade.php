@extends('layouts.app')

@section('title', __('dashboard.industries.show') . ': ' . $industry->title_ar)

@php
    $breadcrumbs = [
        ['label' => __('dashboard.industries.title'), 'url' => route('dashboard.industries.index')],
        ['label' => $industry->title_ar, 'url' => null],
    ];
@endphp

@section('content')

    <div class="mb-6 flex items-center justify-between">
        <h1 class="text-xl font-bold text-[#3D342A]">{{ $industry->title_ar }}</h1>
        <div class="flex items-center gap-3">
            <a href="{{ route('dashboard.industries.edit', $industry) }}">
                <x-buttons.primary>{{ __('dashboard.common.edit') }}</x-buttons.primary>
            </a>
            <a href="{{ route('dashboard.industries.index') }}">
                <x-buttons.secondary>← {{ __('dashboard.common.back') }}</x-buttons.secondary>
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        <div class="space-y-5 lg:col-span-2">
            <div class="rounded-xl border border-[#B49C6E]/20 bg-[#EAEAE9] p-5 space-y-4">
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <p class="text-xs font-semibold uppercase text-[#3D342A]/50">{{ __('dashboard.industries.industry_title') }} (AR)</p>
                        <p class="mt-1 text-sm text-[#3D342A]">{{ $industry->title_ar ?: '—' }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold uppercase text-[#3D342A]/50">{{ __('dashboard.industries.industry_title') }} (EN)</p>
                        <p class="mt-1 text-sm text-[#3D342A]">{{ $industry->title_en ?: '—' }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold uppercase text-[#3D342A]/50">Slug</p>
                        <p class="mt-1 text-sm font-mono text-[#3D342A]/70" dir="ltr">{{ $industry->slug ?: '—' }}</p>
                    </div>
                </div>

                @if($industry->description_ar || $industry->description_en)
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 border-t border-[#B49C6E]/20 pt-4">
                        <div>
                            <p class="text-xs font-semibold uppercase text-[#3D342A]/50">{{ __('dashboard.industries.description') }} (AR)</p>
                            <p class="mt-1 text-sm text-[#3D342A]">{{ $industry->description_ar ?: '—' }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-semibold uppercase text-[#3D342A]/50">{{ __('dashboard.industries.description') }} (EN)</p>
                            <p class="mt-1 text-sm text-[#3D342A]">{{ $industry->description_en ?: '—' }}</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <div class="space-y-5">
            <div class="rounded-xl border border-[#B49C6E]/20 bg-[#EAEAE9] p-5 space-y-3">
                <h3 class="text-sm font-semibold text-[#3D342A]">{{ __('dashboard.common.details') }}</h3>
                <div class="flex items-center justify-between">
                    <span class="text-xs text-[#3D342A]/60">{{ __('dashboard.common.status') }}</span>
                    <span @class(['inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium', 'bg-[#B49C6E]/20 text-[#A38B54]' => $industry->is_active, 'bg-[#3D342A]/10 text-[#3D342A]/50' => !$industry->is_active])>
                        {{ $industry->is_active ? __('dashboard.common.active') : __('dashboard.common.inactive') }}
                    </span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-xs text-[#3D342A]/60">{{ __('dashboard.common.order') }}</span>
                    <span class="text-sm text-[#3D342A]">{{ $industry->order }}</span>
                </div>
            </div>

            @php $image = \App\Helpers\MediaHelper::url($industry, 'industry_images', 'image'); @endphp
            @if($image)
                <div class="rounded-xl border border-[#B49C6E]/20 bg-[#EAEAE9] p-5">
                    <h3 class="mb-3 text-sm font-semibold text-[#3D342A]">{{ __('dashboard.industries.image') }}</h3>
                    <img src="{{ $image }}" alt="{{ $industry->title_ar }}" class="w-full rounded-lg object-cover border border-[#B49C6E]/20">
                </div>
            @endif
        </div>
    </div>

@endsection
