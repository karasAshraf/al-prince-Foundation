@extends('layouts.app')

@php
    $item = $document;
    $breadcrumbs = [
        ['label' => __('dashboard.governance_documents.title'), 'url' => route('dashboard.governance-documents.index')],
        ['label' => $item->title_ar, 'url' => null],
    ];
@endphp

@section('title', $item->title_ar ?? __('dashboard.governance_documents.show'))

@section('content')

    <div class="mb-6 flex items-center justify-between">
        <h1 class="text-xl font-bold text-[#3D342A]">{{ $item->title_ar }}</h1>
        <div class="flex items-center gap-3">
            <a href="{{ route('dashboard.governance-documents.edit', $item) }}">
                <x-buttons.primary>{{ __('dashboard.common.edit') }}</x-buttons.primary>
            </a>
            <a href="{{ route('dashboard.governance-documents.index') }}">
                <x-buttons.secondary>← {{ __('dashboard.common.back') }}</x-buttons.secondary>
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        <div class="space-y-5 lg:col-span-2">
            <div class="rounded-xl border border-[#B49C6E]/20 bg-[#EAEAE9] p-6 shadow-sm">
                <div class="flex items-center gap-4">
                    <div class="flex h-14 w-14 items-center justify-center rounded-xl bg-red-100 text-red-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-[#3D342A]">{{ $item->title_ar }}</h2>
                        @if($item->title_en)
                            <p class="text-sm text-[#3D342A]/60">{{ $item->title_en }}</p>
                        @endif
                    </div>
                </div>

                @if($item->file_path)
                    <div class="mt-6 rounded-xl border border-[#B49C6E]/30 bg-[#EAEAE9]/20 p-4 flex items-center justify-between">
                        <div>
                            <p class="text-sm font-semibold text-[#3D342A]">{{ __('dashboard.common.file') }}</p>
                            <p class="text-xs text-[#3D342A]/60">{{ $item->file_size ? number_format($item->file_size / 1024, 1) . ' KB' : '—' }}</p>
                        </div>
                        <a href="{{ $item->file_path }}" target="_blank" download>
                            <x-buttons.primary>{{ __('dashboard.common.file') }} ↓</x-buttons.primary>
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <div class="space-y-5">
            <div class="rounded-xl border border-[#B49C6E]/20 bg-[#EAEAE9] p-5 shadow-sm">
                <h3 class="mb-4 text-sm font-semibold text-[#3D342A] border-b border-[#B49C6E]/20 pb-2">{{ __('dashboard.common.details') }}</h3>
                <dl class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <dt class="text-[#3D342A]/60">{{ __('dashboard.governance_documents.category') }}</dt>
                        <dd class="font-medium text-[#3D342A]">
                            @if($item->category === 'policies') {{ __('dashboard.governance_documents.categories.policies') }}
                            @elseif($item->category === 'financial_reports') {{ __('dashboard.governance_documents.categories.financial_reports') }}
                            @else {{ __('dashboard.governance_documents.categories.achievement_reports') }} @endif
                        </dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-[#3D342A]/60">{{ __('dashboard.governance_documents.fiscal_year') }}</dt>
                        <dd class="font-medium text-[#3D342A]">{{ $item->fiscal_year }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-[#3D342A]/60">{{ __('dashboard.common.status') }}</dt>
                        <dd class="font-medium text-[#A38B54]">{{ $item->is_active ? __('dashboard.common.active') : __('dashboard.common.inactive') }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-[#3D342A]/60">{{ __('dashboard.common.order') }}</dt>
                        <dd class="font-medium text-[#3D342A]">{{ $item->order }}</dd>
                    </div>
                </dl>
            </div>
        </div>
    </div>

@endsection
