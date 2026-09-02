<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', __('dashboard.auth.login_title')) — {{ config('app.name', __('dashboard.common.foundation_name')) }}</title>

    <!-- Google Fonts Cairo -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>
<body class="relative h-full bg-secondary text-[#3D342A] font-sans antialiased">

    {{-- Language Switcher Top Right --}}
    <div class="absolute top-4 end-4 z-50">
        @if(app()->getLocale() === 'ar')
            <a href="{{ route('lang.switch', ['locale' => 'en', 'scope' => 'dashboard']) }}" class="flex items-center gap-1.5 rounded-lg border border-[#B49C6E]/40 bg-secondary px-3 py-1.5 text-xs font-semibold text-[#3D342A] transition hover:bg-secondary/50 shadow-sm" title="Switch to English">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-[#A38B54]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 21a9 9 0 100-18 9 9 0 000 18zM3.6 9h16.8M3.6 15h16.8" />
                </svg>
                <span>English</span>
            </a>
        @else
            <a href="{{ route('lang.switch', ['locale' => 'ar', 'scope' => 'dashboard']) }}" class="flex items-center gap-1.5 rounded-lg border border-[#B49C6E]/40 bg-secondary px-3 py-1.5 text-xs font-semibold text-[#3D342A] transition hover:bg-secondary/50 shadow-sm" title="التحويل للعربية">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-[#A38B54]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 21a9 9 0 100-18 9 9 0 000 18zM3.6 9h16.8M3.6 15h16.8" />
                </svg>
                <span>العربية</span>
            </a>
        @endif
    </div>

    <div class="flex min-h-screen flex-col items-center justify-center px-4 py-10 sm:px-6">

        {{-- Brand — same identity as the dashboard --}}
        <a href="{{ route('home') }}" class="mb-6 flex items-center justify-center">
            <x-application-logo class="h-14 w-auto object-contain" />
        </a>

        {{-- Centered card --}}
        <div class="w-full max-w-md overflow-hidden rounded-xl border border-[#B49C6E]/30 bg-secondary px-6 py-8 shadow-sm sm:px-8">
            {{ $slot ?? '' }}
            @yield('content')
        </div>

    </div>

</body>
</html>