@extends('layouts.app')

@section('title', __('dashboard.programs.create'))

@php
    $breadcrumbs = [
        ['label' => __('dashboard.programs.title'), 'url' => route('dashboard.programs.index')],
        ['label' => __('dashboard.programs.create'), 'url' => null],
    ];
@endphp

@section('content')

    <h1 class="mb-6 text-xl font-bold text-[#3D342A]">{{ __('dashboard.programs.create') }}</h1>

    <form method="POST" action="{{ route('dashboard.programs.store') }}" enctype="multipart/form-data">
        @include('dashboard.programs._form')
    </form>

@endsection
