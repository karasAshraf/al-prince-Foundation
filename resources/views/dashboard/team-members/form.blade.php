@extends('layouts.app')

@section('title', $member->exists ? 'تعديل عضو' : 'إضافة عضو فريق')

@php
    $breadcrumbs = [
        ['label' => 'فريق العمل', 'url' => route('dashboard.team-members.index')],
        ['label' => $member->exists ? 'تعديل: ' . $member->name_ar : 'إضافة عضو', 'url' => null],
    ];
@endphp

@section('content')
    <div class="mb-6 flex items-center justify-between">
        <h1 class="text-xl font-bold text-[#3D342A]">
            {{ $member->exists ? 'تعديل بيانات العضو' : 'إضافة عضو جديد' }}
        </h1>
        <a href="{{ route('dashboard.team-members.index') }}">
            <x-buttons.secondary>← العودة للقائمة</x-buttons.secondary>
        </a>
    </div>

    <form
        action="{{ $member->exists ? route('dashboard.team-members.update', $member) : route('dashboard.team-members.store') }}"
        method="POST"
        enctype="multipart/form-data"
    >
        @if($member->exists)
            @method('PUT')
        @endif

        @include('dashboard.team-members._form')
    </form>
@endsection
