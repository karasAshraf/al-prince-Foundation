@extends('layouts.app')

@section('title', __('dashboard.news.create'))

@php
    $breadcrumbs = [
        ['label' => __('dashboard.news.title'), 'url' => route('dashboard.news.index')],
        ['label' => __('dashboard.news.create'), 'url' => null],
    ];
@endphp

@section('content')

    <h1 class="mb-6 text-xl font-bold text-[#3D342A]">{{ __('dashboard.news.create') }}</h1>

    <form method="POST" action="{{ route('dashboard.news.store') }}" enctype="multipart/form-data">
        @include('dashboard.news._form')
    </form>

@endsection