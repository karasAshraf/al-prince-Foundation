@extends('layouts.app')

@section('title', __('dashboard.surveys.edit') . ': ' . $survey->title)

@php
    $breadcrumbs = [
        ['label' => __('dashboard.surveys.title'), 'url' => route('dashboard.surveys.index')],
        ['label' => $survey->title, 'url' => route('dashboard.surveys.show', $survey)],
        ['label' => __('dashboard.common.edit'), 'url' => null],
    ];
@endphp

@section('content')
    <div class="mb-6 flex items-center justify-between">
        <h1 class="text-xl font-bold text-[#3D342A]">{{ __('dashboard.surveys.edit') }}: {{ $survey->title }}</h1>
        <a href="{{ route('dashboard.surveys.index') }}">
            <x-buttons.secondary>← {{ __('dashboard.common.back') }}</x-buttons.secondary>
        </a>
    </div>

    <form action="{{ route('dashboard.surveys.update', $survey) }}" method="POST" enctype="multipart/form-data">
        @method('PUT')
        @include('dashboard.surveys._form')
    </form>
@endsection
