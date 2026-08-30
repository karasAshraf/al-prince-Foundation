@extends('layouts.app')

@section('title', __('dashboard.industries.title'))

@php
    $breadcrumbs = [['label' => __('dashboard.industries.title'), 'url' => null]];
@endphp

@section('content')

    <div class="mb-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <h1 class="text-xl font-bold text-[#3D342A]">{{ __('dashboard.industries.title') }}</h1>
        <a href="{{ route('dashboard.industries.create') }}">
            <x-buttons.primary>+ {{ __('dashboard.industries.create') }}</x-buttons.primary>
        </a>
    </div>

    <x-alerts.success />
    <x-alerts.error />

    @include('dashboard.industries._filters')

    @include('dashboard.industries._table')

    <x-modals.delete-modal />

@endsection
