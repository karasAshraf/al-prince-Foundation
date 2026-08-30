@extends('layouts.app')

@section('title', __('dashboard.projects.create'))

@php
    $breadcrumbs = [
        ['label' => __('dashboard.projects.title'), 'url' => route('dashboard.projects.index')],
        ['label' => __('dashboard.projects.create'), 'url' => null],
    ];
@endphp

@section('content')

    <h1 class="mb-6 text-xl font-bold text-[#3D342A]">{{ __('dashboard.projects.create') }}</h1>

    <form method="POST" action="{{ route('dashboard.projects.store') }}" enctype="multipart/form-data">
        @include('dashboard.projects._form')
    </form>

@endsection
