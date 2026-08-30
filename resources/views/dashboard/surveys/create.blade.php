@extends('layouts.app')

@section('title', __('dashboard.surveys.create'))

@php
    $breadcrumbs = [
        ['label' => __('dashboard.surveys.title'), 'url' => route('dashboard.surveys.index')],
        ['label' => __('dashboard.surveys.create'), 'url' => null],
    ];
@endphp

@section('content')
    <div class="mb-6 flex items-center justify-between">
        <h1 class="text-xl font-bold text-[#3D342A]">{{ __('dashboard.surveys.create') }}</h1>
        <a href="{{ route('dashboard.surveys.index') }}">
            <x-buttons.secondary>← {{ __('dashboard.common.back') }}</x-buttons.secondary>
        </a>
    </div>

    <form action="{{ route('dashboard.surveys.store') }}" method="POST" enctype="multipart/form-data">
        @include('dashboard.surveys._form')
    </form>
@endsection
