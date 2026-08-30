<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Dynamic page title — each page can override via @section('title', '...') --}}
    <title>@yield('title', __('dashboard.dashboard.title')) — {{ config('app.name', __('dashboard.common.foundation_name')) }}</title>

    <!-- Google Fonts Cairo -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    {{-- Vite: compiles Tailwind CSS + Alpine.js (and any page-specific JS) --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])


    {{-- Slot for extra per-page <head> content (e.g. a chart library) --}}
    @stack('styles')

    <!-- Native Speculation Rules API for Dashboard Prefetching & Prerendering -->
    <script type="speculationrules">
    {
      "prerender": [
        {
          "source": "document",
          "where": {
            "and": [
              {
                "or": [
                  { "href_matches": "/dashboard" },
                  { "href_matches": "/dashboard/analytics" }
                ]
              }
            ]
          },
          "eagerness": "moderate"
        }
      ],
      "prefetch": [
        {
          "source": "document",
          "where": {
            "and": [
              { "href_matches": "/dashboard*" },
              { "not": { "href_matches": "*/reorder" } },
              { "not": { "href_matches": "*/toggle-status" } },
              { "not": { "href_matches": "/logout" } }
            ]
          },
          "eagerness": "moderate"
        }
      ]
    }
    </script>
</head>

{{--
    x-data holds the ONE piece of shared UI state this shell needs:
    whether the mobile sidebar drawer is open. Navbar toggles it,
    sidebar reads it. Keeping it here (not in navbar or sidebar alone)
    avoids cross-component coupling.
--}}
<body
    x-data="{ sidebarOpen: false }"
    class="h-full bg-[#EAEAE9] text-[#3D342A] font-sans antialiased overflow-hidden"
>
    <div class="h-screen flex flex-col overflow-hidden bg-[#EAEAE9]">

        {{-- ============ NAVBAR ============ --}}
        @include('layouts.navbar')

        {{-- ============ BODY: SIDEBAR + MAIN ============ --}}
        <div class="flex flex-1 overflow-hidden relative">

            {{-- ============ SIDEBAR ============ --}}
            @include('layouts.sidebar')

            {{--
                Mobile backdrop: only visible when sidebarOpen is true on small screens.
                Clicking it closes the sidebar — standard mobile drawer UX.
            --}}
            <div
                x-show="sidebarOpen"
                x-transition.opacity
                @click="sidebarOpen = false"
                class="fixed inset-0 z-30 bg-black/40 lg:hidden"
                x-cloak
                aria-hidden="true"
            ></div>

            {{-- ============ MAIN CONTENT COLUMN ============ --}}
            <div class="flex-1 flex flex-col min-w-0 overflow-hidden h-full">

                {{-- ============ BREADCRUMBS ============ --}}
                @include('layouts.breadcrumbs')

                {{-- ============ SCROLLABLE CONTENT CONTAINER ============ --}}
                <div class="flex-1 overflow-y-auto custom-scrollbar flex flex-col justify-between">

                    {{-- ============ MAIN CONTENT ============ --}}
                    <main class="flex-1 px-4 py-6 sm:px-6 lg:px-8">

                        {{-- Flash messages — one shared location for all dashboard pages --}}
                        @if (session('success'))
                            <div
                                x-data="{ show: true }"
                                x-show="show"
                                x-init="setTimeout(() => show = false, 4000)"
                                x-transition
                                class="mb-6 flex items-start justify-between gap-3 rounded-lg border border-[#B49C6E] bg-[#EAEAE9]/60 px-4 py-3 text-sm text-[#3D342A]"
                                role="alert"
                            >
                                <span>{{ session('success') }}</span>
                                <button @click="show = false" class="text-[#3D342A]/60 hover:text-[#3D342A]" aria-label="{{ __('dashboard.common.close') }}">
                                    &times;
                                </button>
                            </div>
                        @endif

                        @if (session('error'))
                            <div
                                x-data="{ show: true }"
                                x-show="show"
                                x-init="setTimeout(() => show = false, 5000)"
                                x-transition
                                class="mb-6 flex items-start justify-between gap-3 rounded-lg border border-red-300 bg-red-50 px-4 py-3 text-sm text-red-700"
                                role="alert"
                            >
                                <span>{{ session('error') }}</span>
                                <button @click="show = false" class="text-red-400 hover:text-red-600" aria-label="{{ __('dashboard.common.close') }}">
                                    &times;
                                </button>
                            </div>
                        @endif

                        {{-- Validation errors summary (shared across all forms) --}}
                        @if ($errors->any())
                            <div class="mb-6 rounded-lg border border-red-300 bg-red-50 px-4 py-3 text-sm text-red-700">
                                <p class="font-semibold mb-1">{{ __('dashboard.common.validation_errors') }}</p>
                                <ul class="list-disc list-inside space-y-0.5">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        {{-- ============ PAGE CONTENT INJECTION POINT ============ --}}
                        @yield('content')

                    </main>

                    {{-- ============ FOOTER ============ --}}
                    @include('layouts.footer')

                </div>
            </div>
        </div>
    </div>

    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.min.js" defer></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (window.lucide) lucide.createIcons();
        });
    </script>

    {{-- Slot for extra per-page <script> content --}}
    @stack('scripts')
</body>
</html>