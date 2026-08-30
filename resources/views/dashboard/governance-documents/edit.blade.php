@extends('layouts.app')

@section('title', __('dashboard.governance_documents.edit') . ': ' . $document->title_ar)

@php
    $breadcrumbs = [
        ['label' => __('dashboard.governance_documents.title'), 'url' => route('dashboard.governance-documents.index')],
        ['label' => $document->title_ar, 'url' => route('dashboard.governance-documents.show', $document)],
        ['label' => __('dashboard.common.edit'), 'url' => null],
    ];
@endphp

@section('content')
    <div class="mb-6 flex items-center justify-between">
        <h1 class="text-xl font-bold text-[#3D342A]">{{ __('dashboard.governance_documents.edit') }}: {{ $document->title_ar }}</h1>
        <a href="{{ route('dashboard.governance-documents.index') }}">
            <x-buttons.secondary>← {{ __('dashboard.common.back') }}</x-buttons.secondary>
        </a>
    </div>

    <form action="{{ route('dashboard.governance-documents.update', $document) }}" method="POST" enctype="multipart/form-data">
        @method('PUT')
        @include('dashboard.governance-documents._form')
    </form>
@endsection
