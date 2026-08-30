@extends('layouts.app')

@section('title', __('dashboard.services.edit'))

@php
    $breadcrumbs = [
        ['label' => __('dashboard.services.title'), 'url' => route('dashboard.services.index')],
        ['label' => __('dashboard.services.edit') . ': ' . $service->title_ar, 'url' => null],
    ];
@endphp

@section('content')

    <div class="mb-6 flex items-center justify-between">
        <h1 class="text-xl font-bold text-[#3D342A]">{{ __('dashboard.services.edit') }}</h1>
        <a href="{{ route('dashboard.services.index') }}">
            <x-buttons.secondary>← {{ __('dashboard.common.back') }}</x-buttons.secondary>
        </a>
    </div>

    <x-alerts.validation />

    <form method="POST" action="{{ route('dashboard.services.update', $service) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        @php $serviceItem = $service; @endphp
        @include('dashboard.services._form')
    </form>

@endsection
