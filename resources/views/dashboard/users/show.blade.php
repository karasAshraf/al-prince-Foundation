@extends('layouts.app')

@section('title', $user->name)

@php
    $breadcrumbs = [
        ['label' => 'المستخدمون', 'url' => route('dashboard.users.index')],
        ['label' => $user->name, 'url' => null],
    ];
@endphp

@section('content')

    <div class="mb-6 flex items-center justify-between">
        <h1 class="text-xl font-bold text-[#3D342A]">{{ $user->name }}</h1>
        <a href="{{ route('dashboard.users.edit', $user) }}">
            <x-buttons.primary>تعديل</x-buttons.primary>
        </a>
    </div>

    <div class="rounded-xl border border-[#B49C6E]/20 bg-[#EAEAE9] p-5 space-y-3 max-w-lg">
        <div>
            <span class="text-xs text-[#3D342A]/50">الاسم</span>
            <p class="font-medium text-[#3D342A]">{{ $user->name }}</p>
        </div>
        <div>
            <span class="text-xs text-[#3D342A]/50">البريد الإلكتروني</span>
            <p class="font-medium text-[#3D342A]">{{ $user->email }}</p>
        </div>
        <div>
            <span class="text-xs text-[#3D342A]/50">تاريخ الإنشاء</span>
            <p class="font-medium text-[#3D342A]">{{ $user->created_at->format('Y-m-d H:i') }}</p>
        </div>
    </div>

@endsection
