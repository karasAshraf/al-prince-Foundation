@extends('layouts.app')

@section('title', __('dashboard.news.edit'))

@php
    $breadcrumbs = [
        ['label' => __('dashboard.news.title'), 'url' => route('dashboard.news.index')],
        ['label' => __('dashboard.news.edit') . ': ' . $news->title_ar, 'url' => null],
    ];
@endphp

@section('content')

    <h1 class="mb-6 text-xl font-bold text-[#3D342A]">{{ __('dashboard.news.edit') }}</h1>

    <form method="POST" action="{{ route('dashboard.news.update', $news) }}" enctype="multipart/form-data">
        @method('PUT')
        @include('dashboard.news._form')
    </form>

@endsection