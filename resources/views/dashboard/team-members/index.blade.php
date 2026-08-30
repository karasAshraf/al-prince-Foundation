@extends('layouts.app')

@section('title', __('dashboard.team_members.title'))

@php
    $breadcrumbs = [['label' => __('dashboard.team_members.title'), 'url' => null]];
@endphp

@section('content')

    <div class="mb-5 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-xl font-bold text-[#3D342A]">{{ __('dashboard.team_members.title') }}</h1>
        </div>
        <a href="{{ route('dashboard.team-members.create') }}">
            <x-buttons.primary>+ {{ __('dashboard.team_members.create') }}</x-buttons.primary>
        </a>
    </div>

    @include('dashboard.team-members._filters')

    @include('dashboard.team-members._table')

    @include('dashboard.team-members._delete-modal')

@endsection
