@extends('layouts.app')

@section('title', $aboutSection->exists ? 'تعديل قسم عن المؤسسة' : 'إضافة قسم عن المؤسسة')

@php
    $breadcrumbs = [
        ['label' => 'عن المؤسسة', 'url' => route('dashboard.about-sections.index')],
        ['label' => $aboutSection->exists ? 'تعديل' : 'إضافة قسم', 'url' => null],
    ];
@endphp

@section('content')
    <div class="mb-6 flex items-center justify-between">
        <h1 class="text-xl font-bold text-[#3D342A]">
            {{ $aboutSection->exists ? 'تعديل قسم عن المؤسسة' : 'إضافة قسم جديد لصفحة عن المؤسسة' }}
        </h1>
        <a href="{{ route('dashboard.about-sections.index') }}">
            <x-buttons.secondary>← العودة للقائمة</x-buttons.secondary>
        </a>
    </div>

    <form
        action="{{ $aboutSection->exists ? route('dashboard.about-sections.update', $aboutSection) : route('dashboard.about-sections.store') }}"
        method="POST"
        enctype="multipart/form-data"
    >
        @if($aboutSection->exists)
            @method('PUT')
        @endif

        @include('dashboard.about-sections._form')
    </form>
@endsection
