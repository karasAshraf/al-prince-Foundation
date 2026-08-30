@extends('layouts.app')

@section('title', __('dashboard.industries.edit'))

@php
    $breadcrumbs = [
        ['label' => __('dashboard.industries.title'), 'url' => route('dashboard.industries.index')],
        ['label' => __('dashboard.industries.edit') . ': ' . $industry->title_ar, 'url' => null],
    ];
@endphp

@section('content')

    <div class="mb-6 flex items-center justify-between">
        <h1 class="text-xl font-bold text-[#3D342A]">{{ __('dashboard.industries.edit') }}</h1>
        <a href="{{ route('dashboard.industries.index') }}">
            <x-buttons.secondary>← {{ __('dashboard.common.back') }}</x-buttons.secondary>
        </a>
    </div>

    <x-alerts.validation />

    <form method="POST" action="{{ route('dashboard.industries.update', $industry) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        @php $industryItem = $industry; @endphp
        @include('dashboard.industries._form')
    </form>

@endsection
