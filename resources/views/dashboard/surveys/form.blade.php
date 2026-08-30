@extends('layouts.app')

@section('title', $survey->exists ? 'تعديل استبيان' : 'إضافة استبيان جديدة')

@php
    $breadcrumbs = [
        ['label' => 'الاستبيانات', 'url' => route('dashboard.surveys.index')],
        ['label' => $survey->exists ? 'تعديل' : 'إضافة استبيان', 'url' => null],
    ];
@endphp

@section('content')
    <div class="mb-6 flex items-center justify-between">
        <h1 class="text-xl font-bold text-[#3D342A]">
            {{ $survey->exists ? 'تعديل الاستبيان: ' . $survey->title_ar : 'إنشاء استبيان جديد' }}
        </h1>
        <a href="{{ route('dashboard.surveys.index') }}">
            <x-buttons.secondary>← العودة للقائمة</x-buttons.secondary>
        </a>
    </div>

    <form
        action="{{ $survey->exists ? route('dashboard.surveys.update', $survey) : route('dashboard.surveys.store') }}"
        method="POST"
    >
        @if($survey->exists)
            @method('PUT')
        @endif

        @include('dashboard.surveys._form')
    </form>
@endsection
