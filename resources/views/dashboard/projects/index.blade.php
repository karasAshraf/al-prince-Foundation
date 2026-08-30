@extends('layouts.app')

@section('title', __('dashboard.projects.title'))

@php
    $breadcrumbs = [['label' => __('dashboard.projects.title'), 'url' => null]];
@endphp

@section('content')

    <div class="mb-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-xl font-bold text-[#3D342A]">{{ __('dashboard.projects.title') }}</h1>
        </div>
        <a href="{{ route('dashboard.projects.create') }}">
            <x-buttons.primary>+ {{ __('dashboard.projects.create') }}</x-buttons.primary>
        </a>
    </div>

    @include('dashboard.projects._filters')

    @include('dashboard.projects._table')

    <x-modals.delete-modal />

@endsection
