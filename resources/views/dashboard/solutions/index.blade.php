@extends('layouts.app')

@section('title', __('dashboard.solutions.title'))

@php
    $breadcrumbs = [['label' => __('dashboard.solutions.title'), 'url' => null]];
@endphp

@section('content')

    <div class="mb-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <h1 class="text-xl font-bold text-[#3D342A]">{{ __('dashboard.solutions.title') }}</h1>
        <a href="{{ route('dashboard.solutions.create') }}">
            <x-buttons.primary>+ {{ __('dashboard.solutions.create') }}</x-buttons.primary>
        </a>
    </div>

    <x-alerts.success />
    <x-alerts.error />

    @include('dashboard.solutions._filters')

    @include('dashboard.solutions._table')

    <x-modals.delete-modal />

@endsection
