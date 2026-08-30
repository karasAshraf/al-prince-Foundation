@extends('layouts.app')

@section('title', __('dashboard.about_sections.title'))

@php
    $breadcrumbs = [['label' => __('dashboard.about_sections.title'), 'url' => null]];
@endphp

@section('content')

    <div class="mb-5 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-xl font-bold text-[#3D342A]">{{ __('dashboard.about_sections.title') }}</h1>
        </div>
        <a href="{{ route('dashboard.about-sections.create') }}">
            <x-buttons.primary>+ {{ __('dashboard.about_sections.create') }}</x-buttons.primary>
        </a>
    </div>

    @include('dashboard.about-sections._filters')

    @include('dashboard.about-sections._table')

    @include('dashboard.about-sections._delete-modal')

@endsection
