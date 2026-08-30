@extends('layouts.app')

@section('title', __('dashboard.team_members.create'))

@php
    $breadcrumbs = [
        ['label' => __('dashboard.team_members.title'), 'url' => route('dashboard.team-members.index')],
        ['label' => __('dashboard.team_members.create'), 'url' => null],
    ];
@endphp

@section('content')
    <div class="mb-6 flex items-center justify-between">
        <h1 class="text-xl font-bold text-[#3D342A]">{{ __('dashboard.team_members.create') }}</h1>
        <a href="{{ route('dashboard.team-members.index') }}">
            <x-buttons.secondary>← {{ __('dashboard.common.back') }}</x-buttons.secondary>
        </a>
    </div>

    <form action="{{ route('dashboard.team-members.store') }}" method="POST" enctype="multipart/form-data">
        @include('dashboard.team-members._form')
    </form>
@endsection
