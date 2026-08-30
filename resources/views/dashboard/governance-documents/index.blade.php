@extends('layouts.app')

@section('title', __('dashboard.governance_documents.title'))

@php
    $breadcrumbs = [['label' => __('dashboard.governance_documents.title'), 'url' => null]];
@endphp

@section('content')

    <div class="mb-5 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-xl font-bold text-[#3D342A]">{{ __('dashboard.governance_documents.title') }}</h1>
        </div>
        <a href="{{ route('dashboard.governance-documents.create') }}">
            <x-buttons.primary>+ {{ __('dashboard.governance_documents.create') }}</x-buttons.primary>
        </a>
    </div>

    @include('dashboard.governance-documents._filters')

    @include('dashboard.governance-documents._table')

    @include('dashboard.governance-documents._delete-modal')

@endsection
