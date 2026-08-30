@extends('layouts.app')

@section('title', $service->title_ar)

@php
    $breadcrumbs = [
        ['label' => 'الخدمات', 'url' => route('dashboard.services.index')],
        ['label' => $service->title_ar, 'url' => null],
    ];
@endphp

@section('content')

    <div class="mb-6 flex items-center justify-between">
        <h1 class="text-xl font-bold text-[#3D342A]">{{ $service->title_ar }}</h1>
        <div class="flex items-center gap-2">
            <a href="{{ route('dashboard.services.edit', $service) }}">
                <x-buttons.primary>تعديل</x-buttons.primary>
            </a>
            <a href="{{ route('dashboard.services.index') }}">
                <x-buttons.secondary>← العودة للقائمة</x-buttons.secondary>
            </a>
        </div>
    </div>

    <div class="rounded-xl border border-[#B49C6E]/20 bg-[#EAEAE9] p-6 space-y-4">
        <div>
            <h3 class="text-xs text-[#3D342A]/50">اسم الخدمة بالإنجليزية</h3>
            <p class="text-base font-medium text-[#3D342A]">{{ $service->title_en ?? '—' }}</p>
        </div>
        <div>
            <h3 class="text-xs text-[#3D342A]/50">الوصف (عربي)</h3>
            <p class="text-sm text-[#3D342A] mt-1">{{ $service->description_ar ?? '—' }}</p>
        </div>
        <div>
            <h3 class="text-xs text-[#3D342A]/50">الوصف (إنجليزية)</h3>
            <p class="text-sm text-[#3D342A] mt-1">{{ $service->description_en ?? '—' }}</p>
        </div>
    </div>

@endsection
