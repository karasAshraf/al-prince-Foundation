@extends('layouts.app')

@section('title', __('dashboard.partners.create'))

@php
    $breadcrumbs = [
        ['label' => __('dashboard.partners.title'), 'url' => route('dashboard.partners.index')],
        ['label' => __('dashboard.partners.create'), 'url' => null],
    ];
@endphp

@section('content')

    <div class="mb-6 flex items-center justify-between">
        <h1 class="text-xl font-bold text-[#3D342A]">{{ __('dashboard.partners.create') }}</h1>
        <a href="{{ route('dashboard.partners.index') }}">
            <x-buttons.secondary>← {{ __('dashboard.common.back') }}</x-buttons.secondary>
        </a>
    </div>

    <x-alerts.validation />

    <form method="POST" action="{{ route('dashboard.partners.store') }}" enctype="multipart/form-data">
        @include('dashboard.partners._form')
    </form>

@endsection
