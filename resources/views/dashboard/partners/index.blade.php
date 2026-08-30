@extends('layouts.app')

@section('title', __('dashboard.partners.title'))

@php
    $breadcrumbs = [['label' => __('dashboard.partners.title'), 'url' => null]];
@endphp

@section('content')

    <div class="mb-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <h1 class="text-xl font-bold text-[#3D342A]">{{ __('dashboard.partners.title') }}</h1>
        <a href="{{ route('dashboard.partners.create') }}">
            <x-buttons.primary>+ {{ __('dashboard.partners.create') }}</x-buttons.primary>
        </a>
    </div>

    <x-alerts.success />
    <x-alerts.error />

    @include('dashboard.partners._table')

    <x-modals.delete-modal />

@endsection
