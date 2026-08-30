@extends('layouts.app')

@section('title', __('dashboard.about_sections.edit') . ': ' . $aboutSection->title_ar)

@php
    $breadcrumbs = [
        ['label' => __('dashboard.about_sections.title'), 'url' => route('dashboard.about-sections.index')],
        ['label' => $aboutSection->title_ar, 'url' => route('dashboard.about-sections.show', $aboutSection)],
        ['label' => __('dashboard.common.edit'), 'url' => null],
    ];
@endphp

@section('content')
    <div class="mb-6 flex items-center justify-between">
        <h1 class="text-xl font-bold text-[#3D342A]">{{ __('dashboard.about_sections.edit') }}: {{ $aboutSection->title_ar }}</h1>
        <a href="{{ route('dashboard.about-sections.index') }}">
            <x-buttons.secondary>← {{ __('dashboard.common.back') }}</x-buttons.secondary>
        </a>
    </div>

    <form action="{{ route('dashboard.about-sections.update', $aboutSection) }}" method="POST" enctype="multipart/form-data">
        @method('PUT')
        @include('dashboard.about-sections._form')
    </form>
@endsection
