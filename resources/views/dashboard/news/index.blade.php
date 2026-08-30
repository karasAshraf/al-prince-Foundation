@extends('layouts.app')

@section('title', __('dashboard.news.title'))

@php
    $breadcrumbs = [['label' => __('dashboard.news.title'), 'url' => null]];
@endphp

@section('content')

    <div class="mb-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <h1 class="text-xl font-bold text-[#3D342A]">{{ __('dashboard.news.title') }}</h1>
        <a href="{{ route('dashboard.news.create') }}">
            <x-buttons.primary>+ {{ __('dashboard.news.create') }}</x-buttons.primary>
        </a>
    </div>

    @include('dashboard.news._filters')

    @include('dashboard.news._table')

    <x-modals.delete-modal />

@endsection