@extends('layouts.app')

@section('title', __('dashboard.hero_slides.create'))

@php
    $breadcrumbs = [
        ['label' => __('dashboard.hero_slides.title'), 'url' => route('dashboard.hero-slides.index')],
        ['label' => __('dashboard.hero_slides.add_slide'), 'url' => null]
    ];
@endphp

@section('content')

    <div class="mb-5">
        <h1 class="text-xl font-bold text-[#3D342A]">{{ __('dashboard.hero_slides.create') }}</h1>
    </div>

    <form action="{{ route('dashboard.hero-slides.store') }}" method="POST" enctype="multipart/form-data">
        @include('dashboard.hero-slides._form')
    </form>

@endsection
