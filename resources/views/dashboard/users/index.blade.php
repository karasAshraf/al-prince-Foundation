@extends('layouts.app')

@section('title', __('dashboard.users.title'))

@php
    $breadcrumbs = [['label' => __('dashboard.users.title'), 'url' => null]];
@endphp

@section('content')

    <div class="mb-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <h1 class="text-xl font-bold text-[#3D342A]">{{ __('dashboard.users.title') }}</h1>
        <a href="{{ route('dashboard.users.create') }}">
            <x-buttons.primary>+ {{ __('dashboard.users.create') }}</x-buttons.primary>
        </a>
    </div>

    <x-alerts.success />
    <x-alerts.error />

    @include('dashboard.users._table')

    <x-modals.delete-modal />

@endsection
