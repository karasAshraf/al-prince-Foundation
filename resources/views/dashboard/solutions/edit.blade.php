@extends('layouts.app')

@section('title', __('dashboard.solutions.edit'))

@php
    $breadcrumbs = [
        ['label' => __('dashboard.solutions.title'), 'url' => route('dashboard.solutions.index')],
        ['label' => __('dashboard.solutions.edit') . ': ' . $solution->title_ar, 'url' => null],
    ];
@endphp

@section('content')

    <div class="mb-6 flex items-center justify-between">
        <h1 class="text-xl font-bold text-[#3D342A]">{{ __('dashboard.solutions.edit') }}</h1>
        <a href="{{ route('dashboard.solutions.index') }}">
            <x-buttons.secondary>← {{ __('dashboard.common.back') }}</x-buttons.secondary>
        </a>
    </div>

    <x-alerts.validation />

    <form method="POST" action="{{ route('dashboard.solutions.update', $solution) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        @php $solutionItem = $solution; @endphp
        @include('dashboard.solutions._form')
    </form>

@endsection
