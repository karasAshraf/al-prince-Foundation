@extends('layouts.app')

@section('title', $section->exists ? 'تعديل قسم الصفحة الرئيسية' : 'إضافة قسم جديد')

@php
    $breadcrumbs = [
        ['label' => 'أقسام الرئيسية', 'url' => route('dashboard.home-sections.index')],
        ['label' => $section->exists ? 'تعديل' : 'إضافة قسم', 'url' => null],
    ];
@endphp

@section('content')
    <div class="mb-6 flex items-center justify-between">
        <h1 class="text-xl font-bold text-[#3D342A]">
            {{ $section->exists ? 'تعديل بيانات القسم' : 'إضافة قسم جديد للصفحة الرئيسية' }}
        </h1>
        <a href="{{ route('dashboard.home-sections.index') }}">
            <x-buttons.secondary>← العودة للقائمة</x-buttons.secondary>
        </a>
    </div>

    <form
        action="{{ $section->exists ? route('dashboard.home-sections.update', $section) : route('dashboard.home-sections.store') }}"
        method="POST"
        enctype="multipart/form-data"
    >
        @if($section->exists)
            @method('PUT')
        @endif

        @include('dashboard.home-sections._form')
    </form>
@endsection
