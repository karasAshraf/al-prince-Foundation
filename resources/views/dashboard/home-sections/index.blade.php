@extends('layouts.app')

@section('title', __('dashboard.home_sections.title'))

@php
    $breadcrumbs = [['label' => __('dashboard.home_sections.title'), 'url' => null]];
@endphp

@section('content')

    <div class="mb-5 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-xl font-bold text-[#3D342A]">{{ __('dashboard.home_sections.title') }}</h1>
        </div>
        <a href="{{ route('dashboard.home-sections.create') }}">
            <x-buttons.primary>+ {{ __('dashboard.home_sections.create') }}</x-buttons.primary>
        </a>
    </div>

    @include('dashboard.home-sections._filters')

    @include('dashboard.home-sections._table')

    @include('dashboard.home-sections._delete-modal')

@endsection
