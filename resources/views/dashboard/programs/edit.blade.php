@extends('layouts.app')

@section('title', __('dashboard.programs.edit'))

@php
    $breadcrumbs = [
        ['label' => __('dashboard.programs.title'), 'url' => route('dashboard.programs.index')],
        ['label' => __('dashboard.programs.edit') . ': ' . $program->title_ar, 'url' => null],
    ];
@endphp

@section('content')

    <h1 class="mb-6 text-xl font-bold text-[#3D342A]">{{ __('dashboard.programs.edit') }}</h1>

    <form method="POST" action="{{ route('dashboard.programs.update', $program) }}" enctype="multipart/form-data">
        @method('PUT')
        @include('dashboard.programs._form')
    </form>

@endsection
