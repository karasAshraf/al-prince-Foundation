@extends('layouts.app')

@section('title', __('dashboard.surveys.title'))

@php
    $breadcrumbs = [['label' => __('dashboard.surveys.title'), 'url' => null]];
@endphp

@section('content')

    <div class="mb-5 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-xl font-bold text-[#3D342A]">{{ __('dashboard.surveys.title') }}</h1>
        </div>
        <a href="{{ route('dashboard.surveys.create') }}">
            <x-buttons.primary>+ {{ __('dashboard.surveys.create') }}</x-buttons.primary>
        </a>
    </div>

    @include('dashboard.surveys._filters')

    @include('dashboard.surveys._table')

    @include('dashboard.surveys._delete-modal')

@endsection
