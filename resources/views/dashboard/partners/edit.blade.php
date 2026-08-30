@extends('layouts.app')

@section('title', __('dashboard.partners.edit'))

@php
    $breadcrumbs = [
        ['label' => __('dashboard.partners.title'), 'url' => route('dashboard.partners.index')],
        ['label' => __('dashboard.partners.edit') . ': ' . $partner->name_ar, 'url' => null],
    ];
@endphp

@section('content')

    <div class="mb-6 flex items-center justify-between">
        <h1 class="text-xl font-bold text-[#3D342A]">{{ __('dashboard.partners.edit') }}</h1>
        <a href="{{ route('dashboard.partners.index') }}">
            <x-buttons.secondary>← {{ __('dashboard.common.back') }}</x-buttons.secondary>
        </a>
    </div>

    <x-alerts.validation />

    <form method="POST" action="{{ route('dashboard.partners.update', $partner) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        @php $partnerItem = $partner; @endphp
        @include('dashboard.partners._form')
    </form>

@endsection
