@extends('layouts.app')

@section('title', __('dashboard.programs.title'))

@php
    $breadcrumbs = [['label' => __('dashboard.programs.title'), 'url' => null]];
@endphp

@section('content')

    <div class="mb-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <h1 class="text-xl font-bold text-[#3D342A]">{{ __('dashboard.programs.title') }}</h1>
        <a href="{{ route('dashboard.programs.create') }}">
            <x-buttons.primary>+ {{ __('dashboard.programs.create') }}</x-buttons.primary>
        </a>
    </div>

    @include('dashboard.programs._filters')

    @include('dashboard.programs._table')

    <x-modals.delete-modal />

@endsection
