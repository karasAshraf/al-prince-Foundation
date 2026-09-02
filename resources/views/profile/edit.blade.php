@extends('layouts.app')

@section('title', __('dashboard.profile.title'))

@php
    $breadcrumbs = [
        ['label' => __('dashboard.profile.title'), 'url' => null]
    ];
@endphp

@section('content')
    <div class="mb-6">
        <h1 class="text-xl font-bold text-[#3D342A]">{{ __('dashboard.profile.title') }}</h1>
        <p class="text-xs text-[#3D342A]/60 mt-1">{{ __('dashboard.profile.subtitle') }}</p>
    </div>

    <div class="space-y-6 max-w-2xl">
        {{-- Profile Information Card --}}
        <div class="rounded-xl border border-[#B49C6E]/20 bg-secondary p-5 shadow-sm space-y-4">
            <h3 class="text-sm font-bold text-[#3D342A] pb-2 border-b border-[#B49C6E]/10">
                {{ __('dashboard.profile.info') }}
            </h3>

            <form method="post" action="{{ route('profile.update') }}" class="space-y-4">
                @csrf
                @method('patch')

                <x-forms.input
                    name="name"
                    label="{{ __('dashboard.users.name') }}"
                    :value="old('name', $user->name)"
                    required
                />

                <x-forms.input
                    name="email"
                    label="{{ __('dashboard.users.email') }}"
                    type="email"
                    :value="old('email', $user->email)"
                    required
                />

                <div class="flex items-center gap-3 pt-2">
                    <x-buttons.primary type="submit">
                        {{ __('dashboard.profile.save') }}
                    </x-buttons.primary>

                    @if (session('status') === 'profile-updated')
                        <span class="text-xs text-green-600 font-semibold">{{ __('Saved.') }}</span>
                    @endif
                </div>
            </form>
        </div>

        {{-- Password Update Card --}}
        <div class="rounded-xl border border-[#B49C6E]/20 bg-secondary p-5 shadow-sm space-y-4">
            <h3 class="text-sm font-bold text-[#3D342A] pb-2 border-b border-[#B49C6E]/10">
                {{ __('dashboard.profile.update_password') }}
            </h3>

            <form method="post" action="{{ route('password.update') }}" class="space-y-4">
                @csrf
                @method('put')

                <x-forms.input
                    name="current_password"
                    label="{{ __('dashboard.profile.current_password') }}"
                    type="password"
                    required
                />

                <x-forms.input
                    name="password"
                    label="{{ __('dashboard.profile.new_password') }}"
                    type="password"
                    required
                />

                <x-forms.input
                    name="password_confirmation"
                    label="{{ __('dashboard.profile.confirm_new_password') }}"
                    type="password"
                    required
                />

                <div class="flex items-center gap-3 pt-2">
                    <x-buttons.primary type="submit">
                        {{ __('dashboard.profile.update_password') }}
                    </x-buttons.primary>

                    @if (session('status') === 'password-updated')
                        <span class="text-xs text-green-600 font-semibold">{{ __('Saved.') }}</span>
                    @endif
                </div>
            </form>
        </div>
    </div>
@endsection
