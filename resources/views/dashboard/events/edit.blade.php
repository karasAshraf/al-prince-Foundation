@extends('layouts.app')

@section('title', __('dashboard.events.edit'))

@php
    $breadcrumbs = [
        ['label' => __('dashboard.events.title'), 'url' => route('dashboard.events.index')],
        ['label' => __('dashboard.events.edit') . ': ' . $event->title_ar, 'url' => null],
    ];
@endphp

@section('content')

    <div class="mb-6 flex items-center justify-between">
        <h1 class="text-xl font-bold text-[#3D342A]">{{ __('dashboard.events.edit') }}</h1>
        <a href="{{ route('dashboard.events.index') }}">
            <x-buttons.secondary>← {{ __('dashboard.common.back') }}</x-buttons.secondary>
        </a>
    </div>

    <x-alerts.validation />

    <form method="POST" action="{{ route('dashboard.events.update', $event) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        @php $eventItem = $event; @endphp
        @include('dashboard.events._form')
    </form>

@endsection
