{{--
    Navbar — fixed top bar shared across all dashboard pages.
    Depends on `sidebarOpen` (declared in layouts/app.blade.php <body> x-data)
    for the mobile menu toggle button below.
--}}
<header class="flex-none h-16 w-full shrink-0 z-40 border-b border-[#B7B5B3] bg-white">
    <nav
        class="flex h-16 items-center justify-between gap-4 px-4 sm:px-6 lg:px-8"
        aria-label="{{ __('dashboard.common.top_navbar_aria') }}"
    >

        {{-- Left side: mobile menu button + brand --}}
        <div class="flex items-center gap-3">

            {{-- Mobile-only hamburger --}}
            <button
                type="button"
                @click="sidebarOpen = !sidebarOpen"
                class="inline-flex items-center justify-center rounded-md p-2 text-[#5C5450] hover:bg-[#EAEAE9] lg:hidden"
                aria-label="{{ __('dashboard.common.open_sidebar') }}"
            >
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="h-6 w-6"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke-width="1.8"
                    stroke="currentColor"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M3.75 6.75h16.5M3.75 12h16.5M3.75 17.25h16.5"
                    />
                </svg>
            </button>

            {{-- Brand / logo --}}
            <a href="{{ route('dashboard.home') }}" class="flex items-center gap-2">
                <x-application-logo
                    class="h-16 sm:h-20 lg:h-28 w-auto  object-contain " />
            </a>

        </div>

        {{-- Right side: language switcher + user menu --}}
        <div class="flex items-center gap-3">

            {{-- Language Switcher Button --}}
            <div class="flex items-center">
                @if(app()->getLocale() === 'ar')
                    <a href="{{ route('lang.switch', ['locale' => 'en', 'scope' => 'dashboard']) }}" class="flex items-center gap-1.5 rounded-lg border border-[#B7B5B3] bg-white px-3 py-1.5 text-xs font-semibold text-[#5C5450] transition hover:bg-[#EAEAE9] shadow-sm" title="Switch to English">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-[#A38B54]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 21a9 9 0 100-18 9 9 0 000 18zM3.6 9h16.8M3.6 15h16.8" />
                        </svg>
                        <span>English</span>
                    </a>
                @else
                    <a href="{{ route('lang.switch', ['locale' => 'ar', 'scope' => 'dashboard']) }}" class="flex items-center gap-1.5 rounded-lg border border-[#B7B5B3] bg-white px-3 py-1.5 text-xs font-semibold text-[#5C5450] transition hover:bg-[#EAEAE9] shadow-sm" title="التحويل للعربية">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-[#A38B54]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 21a9 9 0 100-18 9 9 0 000 18zM3.6 9h16.8M3.6 15h16.8" />
                        </svg>
                        <span>العربية</span>
                    </a>
                @endif
            </div>

            {{-- User dropdown — its own isolated Alpine scope --}}
            <div x-data="{ userMenuOpen: false }" class="relative">
                <button
                    type="button"
                    @click="userMenuOpen = !userMenuOpen"
                    class="flex items-center gap-2 rounded-full py-1 pe-2 ps-1 hover:bg-[#EAEAE9]"
                    :aria-expanded="userMenuOpen.toString()"
                    aria-haspopup="true"
                >
                    {{-- Avatar: first letter of the user's name --}}
                    <span class="flex h-8 w-8 items-center justify-center rounded-full bg-[#B49C6E] text-sm font-bold text-[#3D342A]">
                        {{ Str::substr(auth()->user()->name, 0, 1) }}
                    </span>
                    <span class="hidden text-sm font-medium text-[#5C5450] sm:inline">
                        {{ auth()->user()->name }}
                    </span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-[#3D342A]/60" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                    </svg>
                </button>

                {{-- Dropdown panel --}}
                <div
                    x-show="userMenuOpen"
                    x-transition
                    @click.outside="userMenuOpen = false"
                    x-cloak
                    class="absolute end-0 z-50 mt-2 w-48 overflow-hidden rounded-lg border border-[#B7B5B3] bg-white shadow-lg"
                >
                    <a
                        href="{{ route('profile.edit') }}"
                        class="block px-4 py-2 text-sm text-[#5C5450] hover:bg-[#EAEAE9]"
                    >
                        {{ __('dashboard.common.profile') }}
                    </a>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button
                            type="submit"
                            class="block w-full px-4 py-2 text-start text-sm text-[#5C5450] hover:bg-[#EAEAE9]"
                        >
                            {{ __('dashboard.common.logout') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>
</header>