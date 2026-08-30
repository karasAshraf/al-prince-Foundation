@extends('layouts.app')

@section('title', __('dashboard.projects.edit'))

@php
    $breadcrumbs = [
        ['label' => __('dashboard.projects.title'), 'url' => route('dashboard.projects.index')],
        ['label' => __('dashboard.projects.edit') . ': ' . $project->title_ar, 'url' => null],
    ];
@endphp

@section('content')

    <h1 class="mb-6 text-xl font-bold text-[#3D342A]">{{ __('dashboard.projects.edit') }}</h1>

    <form method="POST" action="{{ route('dashboard.projects.update', $project) }}" enctype="multipart/form-data">
        @method('PUT')
        @include('dashboard.projects._form')
    </form>

@endsection
