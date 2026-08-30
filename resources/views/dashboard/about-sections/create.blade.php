@extends('layouts.app')

@section('title', __('dashboard.about_sections.create'))

@php
    $breadcrumbs = [
        ['label' => __('dashboard.about_sections.title'), 'url' => route('dashboard.about-sections.index')],
        ['label' => __('dashboard.about_sections.create'), 'url' => null],
    ];
@endphp

@section('content')
    <div class="mb-6 flex items-center justify-between">
        <h1 class="text-xl font-bold text-[#3D342A]">{{ __('dashboard.about_sections.create') }}</h1>
        <a href="{{ route('dashboard.about-sections.index') }}">
            <x-buttons.secondary>← {{ __('dashboard.common.back') }}</x-buttons.secondary>
        </a>
    </div>

    <form action="{{ route('dashboard.about-sections.store') }}" method="POST" enctype="multipart/form-data">
        @include('dashboard.about-sections._form')
    </form>
@endsection
