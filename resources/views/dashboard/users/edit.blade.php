@extends('layouts.app')

@section('title', __('dashboard.users.edit'))

@php
    $breadcrumbs = [
        ['label' => __('dashboard.users.title'), 'url' => route('dashboard.users.index')],
        ['label' => __('dashboard.users.edit') . ': ' . $user->name, 'url' => null],
    ];
@endphp

@section('content')

    <div class="mb-6 flex items-center justify-between">
        <h1 class="text-xl font-bold text-[#3D342A]">{{ __('dashboard.users.edit') }}</h1>
        <a href="{{ route('dashboard.users.index') }}">
            <x-buttons.secondary>← {{ __('dashboard.common.back') }}</x-buttons.secondary>
        </a>
    </div>

    <x-alerts.validation />

    <form method="POST" action="{{ route('dashboard.users.update', $user) }}">
        @csrf
        @method('PUT')
        @include('dashboard.users._form')
    </form>

@endsection
