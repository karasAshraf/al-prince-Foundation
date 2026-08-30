@extends('layouts.app')

@section('title', __('dashboard.home_sections.create'))

@php
    $breadcrumbs = [
        ['label' => __('dashboard.home_sections.title'), 'url' => route('dashboard.home-sections.index')],
        ['label' => __('dashboard.home_sections.create'), 'url' => null],
    ];
@endphp

@section('content')
    <div class="mb-6 flex items-center justify-between">
        <h1 class="text-xl font-bold text-[#3D342A]">{{ __('dashboard.home_sections.create') }}</h1>
        <a href="{{ route('dashboard.home-sections.index') }}">
            <x-buttons.secondary>← {{ __('dashboard.common.back') }}</x-buttons.secondary>
        </a>
    </div>

    <form action="{{ route('dashboard.home-sections.store') }}" method="POST" enctype="multipart/form-data">
        @include('dashboard.home-sections._form')
    </form>
@endsection
