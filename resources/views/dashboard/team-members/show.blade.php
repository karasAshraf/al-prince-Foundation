@extends('layouts.app')

@section('title', $item->name_ar ?? __('dashboard.team_members.show'))

@php
    $breadcrumbs = [
        ['label' => __('dashboard.team_members.title'), 'url' => route('dashboard.team-members.index')],
        ['label' => $item->name_ar, 'url' => null],
    ];
@endphp

@section('content')

    <div class="mb-6 flex items-center justify-between">
        <h1 class="text-xl font-bold text-[#3D342A]">{{ $item->name_ar }}</h1>
        <div class="flex items-center gap-3">
            <a href="{{ route('dashboard.team-members.edit', $item) }}">
                <x-buttons.primary>{{ __('dashboard.common.edit') }}</x-buttons.primary>
            </a>
            <a href="{{ route('dashboard.team-members.index') }}">
                <x-buttons.secondary>← {{ __('dashboard.common.back') }}</x-buttons.secondary>
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        <div class="space-y-5 lg:col-span-2">
            <div class="rounded-xl border border-[#B49C6E]/20 bg-[#EAEAE9] p-6 shadow-sm">
                <div class="flex items-start gap-5">
                    <x-forms.image-preview :url="\App\Helpers\MediaHelper::url($item, 'team_photos', 'image')" size="lg" />
                    <div>
                        <h2 class="text-xl font-bold text-[#3D342A]">{{ $item->name_ar }}</h2>
                        @if($item->name_en)
                            <p class="text-sm text-[#3D342A]/60">{{ $item->name_en }}</p>
                        @endif
                        <p class="mt-2 inline-block rounded-md bg-[#A38B54]/10 px-3 py-1 text-sm font-semibold text-[#A38B54]">
                            {{ $item->position_ar }}
                        </p>
                    </div>
                </div>

                @if($item->bio_ar)
                    <div class="mt-6 border-t border-[#B49C6E]/20 pt-4">
                        <h3 class="text-sm font-semibold text-[#3D342A] mb-2">{{ __('dashboard.team_members.bio') }} (AR)</h3>
                        <p class="text-sm leading-relaxed text-[#3D342A]/80 whitespace-pre-line">{{ $item->bio_ar }}</p>
                    </div>
                @endif

                @if($item->bio_en)
                    <div class="mt-4 border-t border-[#B49C6E]/20 pt-4">
                        <h3 class="text-sm font-semibold text-[#3D342A] mb-2">{{ __('dashboard.team_members.bio') }} (EN)</h3>
                        <p class="text-sm leading-relaxed text-[#3D342A]/80 whitespace-pre-line">{{ $item->bio_en }}</p>
                    </div>
                @endif
            </div>
        </div>

        <div class="space-y-5">
            <div class="rounded-xl border border-[#B49C6E]/20 bg-[#EAEAE9] p-5 shadow-sm">
                <h3 class="mb-4 text-sm font-semibold text-[#3D342A] border-b border-[#B49C6E]/20 pb-2">{{ __('dashboard.common.details') }}</h3>
                <dl class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <dt class="text-[#3D342A]/60">{{ __('dashboard.common.type') }}</dt>
                        <dd class="font-medium text-[#3D342A]">{{ $item->type === 'board' ? __('dashboard.team_members.type_board') : __('dashboard.team_members.type_executive') }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-[#3D342A]/60">{{ __('dashboard.common.status') }}</dt>
                        <dd class="font-medium text-[#A38B54]">{{ $item->is_active ? __('dashboard.common.active') : __('dashboard.common.inactive') }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-[#3D342A]/60">{{ __('dashboard.common.order') }}</dt>
                        <dd class="font-medium text-[#3D342A]">{{ $item->order }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-[#3D342A]/60">{{ __('dashboard.common.created_at') }}</dt>
                        <dd class="font-medium text-[#3D342A]">{{ $item->created_at?->format('Y-m-d') }}</dd>
                    </div>
                </dl>
            </div>
        </div>
    </div>

@endsection
