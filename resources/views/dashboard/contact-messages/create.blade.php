@extends('layouts.app')

@section('title', 'عرض رسالة تواصل')

@php
    $breadcrumbs = [
        ['label' => 'رسائل التواصل', 'url' => route('dashboard.contact-messages.index')],
        ['label' => 'عرض التفاصيل', 'url' => null],
    ];
@endphp

@section('content')
    <div class="mb-6 flex items-center justify-between">
        <h1 class="text-xl font-bold text-[#3D342A]">بيانات رسالة التواصل والتظلمات</h1>
        <a href="{{ route('dashboard.contact-messages.index') }}">
            <x-buttons.secondary>← العودة للقائمة</x-buttons.secondary>
        </a>
    </div>

    @include('dashboard.contact-messages._form')
@endsection
