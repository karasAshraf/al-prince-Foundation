@extends('layouts.app')

@section('title', $partner->name_ar)

@php
    $breadcrumbs = [
        ['label' => __('dashboard.partners.title'), 'url' => route('dashboard.partners.index')],
        ['label' => $partner->name_ar, 'url' => null],
    ];
@endphp

@section('content')

    <div class="mb-6 flex items-center justify-between">
        <h1 class="text-xl font-bold text-[#3D342A]">{{ $partner->name_ar }}</h1>
        <div class="flex items-center gap-2">
            <a href="{{ route('dashboard.partners.edit', $partner) }}">
                <x-buttons.primary>تعديل</x-buttons.primary>
            </a>
            <a href="{{ route('dashboard.partners.index') }}">
                <x-buttons.secondary>← العودة للقائمة</x-buttons.secondary>
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        <div class="rounded-xl border border-[#B49C6E]/20 bg-[#EAEAE9] p-6 space-y-4 lg:col-span-2">
            <div>
                <h3 class="text-xs text-[#3D342A]/50">اسم الشريك بالعربية</h3>
                <p class="text-base font-medium text-[#3D342A]">{{ $partner->name_ar }}</p>
            </div>
            <div>
                <h3 class="text-xs text-[#3D342A]/50">اسم الشريك بالإنجليزية</h3>
                <p class="text-base font-medium text-[#3D342A]">{{ $partner->name_en ?? '—' }}</p>
            </div>
            @if($partner->external_link)
                <div>
                    <h3 class="text-xs text-[#3D342A]/50">رابط الشريك</h3>
                    <a href="{{ $partner->external_link }}" target="_blank" class="text-sm text-[#A38B54] hover:underline break-all mt-1 block">
                        {{ $partner->external_link }}
                    </a>
                </div>
            @endif
        </div>

        <div class="rounded-xl border border-[#B49C6E]/20 bg-[#EAEAE9] p-6 text-center space-y-3">
            <h3 class="text-xs text-[#3D342A]/50">شعار الشريك</h3>
            <div class="mx-auto flex h-40 w-40 items-center justify-center rounded-lg border border-[#B49C6E]/20 bg-[#EAEAE9] p-2">
                <img
                    src="{{ \App\Helpers\MediaHelper::url($partner, 'partner_logos', 'image') }}"
                    alt="{{ $partner->name_ar }}"
                    class="max-h-full max-w-full object-contain"
                >
            </div>
        </div>
    </div>

@endsection
