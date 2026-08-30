@extends('layouts.guest')

@section('title', __('dashboard.auth.login_title'))
 
@section('content')

    <h1 class="mb-6 text-center text-xl font-semibold text-[#3D342A]">
        {{ __('dashboard.auth.login_heading') }}
    </h1>

    {{-- Session status (e.g. password reset confirmation) --}}
    @if (session('status'))
        <div class="mb-4 rounded-lg border border-[#B49C6E] bg-[#EAEAE9]/60 px-4 py-3 text-sm text-[#3D342A]">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        {{-- Email --}}
        <div>
            <label for="email" class="mb-1.5 block text-sm font-medium text-[#3D342A]">
                {{ __('dashboard.auth.email') }}
            </label>
            <input
                id="email"
                type="email"
                name="email"
                value="{{ old('email') }}"
                required
                autofocus
                autocomplete="username"
                class="w-full rounded-lg border border-[#B49C6E]/40 bg-[#EAEAE9] px-3 py-2 text-sm text-[#3D342A] focus:border-[#A38B54] focus:outline-none focus:ring-1 focus:ring-[#A38B54]"
            >
            @error('email')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        {{-- Password --}}
        <div>
            <label for="password" class="mb-1.5 block text-sm font-medium text-[#3D342A]">
                {{ __('dashboard.auth.password') }}
            </label>
            <input
                id="password"
                type="password"
                name="password"
                required
                autocomplete="current-password"
                class="w-full rounded-lg border border-[#B49C6E]/40 bg-[#EAEAE9] px-3 py-2 text-sm text-[#3D342A] focus:border-[#A38B54] focus:outline-none focus:ring-1 focus:ring-[#A38B54]"
            >
            @error('password')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        {{-- Remember me --}}
        <div class="flex items-center justify-between">
            <label class="flex items-center gap-2 text-sm text-[#3D342A]">
                <input
                    type="checkbox"
                    name="remember"
                    class="rounded border-[#B49C6E]/40 text-[#A38B54] focus:ring-[#A38B54]"
                >
                {{ __('dashboard.auth.remember_me') }}
            </label>

            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="text-sm text-[#A38B54] hover:underline">
                    {{ __('dashboard.auth.forgot_password') }}
                </a>
            @endif
        </div>

        {{-- Submit --}}
        <button
            type="submit"
            class="w-full rounded-lg bg-[#A38B54] px-4 py-2.5 text-sm font-semibold text-[#EAEAE9] hover:bg-[#A38B54]/90 focus:outline-none focus:ring-2 focus:ring-[#A38B54] focus:ring-offset-2"
        >
            {{ __('dashboard.auth.login_button') }}
        </button>
    </form>

@endsection