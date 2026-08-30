@extends('layouts.app')

@section('title', __('dashboard.activities.edit'))

@php
    $breadcrumbs = [
        ['label' => __('dashboard.activities.title'), 'url' => route('dashboard.activities.index')],
        ['label' => __('dashboard.activities.edit') . ': ' . $activity->title_ar, 'url' => null],
    ];
@endphp

@section('content')

    <div class="mb-6 flex items-center justify-between">
        <h1 class="text-xl font-bold text-[#3D342A]">{{ __('dashboard.activities.edit') }}</h1>
        <a href="{{ route('dashboard.activities.index') }}">
            <x-buttons.secondary>← {{ __('dashboard.common.back') }}</x-buttons.secondary>
        </a>
    </div>

    <x-alerts.validation />

    <form method="POST" action="{{ route('dashboard.activities.update', $activity) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        @php $activityItem = $activity; @endphp
        @include('dashboard.activities._form')
    </form>

@endsection
