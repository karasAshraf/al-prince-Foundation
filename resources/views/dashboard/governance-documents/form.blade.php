@extends('layouts.app')

@section('title', $document->exists ? 'تعديل وثيقة' : 'إضافة وثيقة حوكمة')

@php
    $breadcrumbs = [
        ['label' => 'وثائق الحوكمة', 'url' => route('dashboard.governance-documents.index')],
        ['label' => $document->exists ? 'تعديل' : 'إضافة وثيقة', 'url' => null],
    ];
@endphp

@section('content')
    <div class="mb-6 flex items-center justify-between">
        <h1 class="text-xl font-bold text-[#3D342A]">
            {{ $document->exists ? 'تعديل الوثيقة' : 'إضافة وثيقة حوكمة جديدة' }}
        </h1>
        <a href="{{ route('dashboard.governance-documents.index') }}">
            <x-buttons.secondary>← العودة للقائمة</x-buttons.secondary>
        </a>
    </div>

    <form
        action="{{ $document->exists ? route('dashboard.governance-documents.update', $document) : route('dashboard.governance-documents.store') }}"
        method="POST"
        enctype="multipart/form-data"
    >
        @if($document->exists)
            @method('PUT')
        @endif

        @include('dashboard.governance-documents._form')
    </form>
@endsection
