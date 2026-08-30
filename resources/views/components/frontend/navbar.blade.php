@props([
    'brandName' => __('frontend.brand_name'),
    'companyInfo' => null,
])

@php
    $locale = app()->getLocale();
    $companyInfo = $companyInfo ?? [];

    // Resolve Logo Path
    $logoUrl = null;
    $dbLogo = $companyInfo['logo'] ?? null;
    if ($dbLogo) {
        if (preg_match('/^(https?:)?\/\//i', $dbLogo)) {
            $logoUrl = $dbLogo;
        } elseif (\Illuminate\Support\Facades\Storage::disk('public')->exists($dbLogo)) {
            $logoUrl = \Illuminate\Support\Facades\Storage::url($dbLogo);
        }
    }

    // Fallbacks
    if (!$logoUrl) {
        if (file_exists(public_path('storage/1WyMxRZqU0uYwVafYJjSvYoDjD6VF9ebjDJugRBF'))) {
            $logoUrl = asset('storage/1WyMxRZqU0uYwVafYJjSvYoDjD6VF9ebjDJugRBF');
        }
    }
    if (!$logoUrl) {
        try {
            $logoFiles = \Illuminate\Support\Facades\Storage::disk('public')->files('logo');
            if (!empty($logoFiles)) {
                $logoUrl = \Illuminate\Support\Facades\Storage::url($logoFiles[0]);
            }
        } catch (\Throwable $e) {}
    }
    if (!$logoUrl) {
        try {
            $allPublicFiles = \Illuminate\Support\Facades\Storage::disk('public')->files();
            foreach ($allPublicFiles as $file) {
                if (preg_match('/\.(png|jpe?g|svg|webp)$/i', $file)) {
                    $logoUrl = \Illuminate\Support\Facades\Storage::url($file);
                    break;
                }
            }
        } catch (\Throwable $e) {}
    }
    if (!$logoUrl && file_exists(public_path('storage/logo/لوجو-02.png'))) {
        $logoUrl = asset('storage/logo/لوجو-02.png');
    }

    $navItems = [
        ['label' => __('frontend.surveys'),    'route' => 'surveys.index',   'active' => request()->routeIs('surveys.*')],
        ['label' => __('frontend.contact'),    'route' => 'contact.index',   'active' => request()->routeIs('contact.*')],
    ];
    $aboutActive = request()->routeIs('about.*') || request()->routeIs('governance.*');
    $servicesActive = request()->routeIs('content-services.*') || request()->routeIs('services.*') || request()->routeIs('activities.*') || request()->routeIs('industries.*') || request()->routeIs('solutions.*');
    $advActive = request()->routeIs('advertising-center.*') || request()->routeIs('news.*') || request()->routeIs('events.*') || request()->routeIs('media-library.*');
@endphp

<header {{ $attributes->merge(['class' => 'w-full bg-surface/95 dark:bg-gray-900/95 backdrop-blur-md border-b border-primary-light/20 sticky top-0 z-50 transition-all duration-200']) }}
        x-data="{ mobileOpen: false, aboutOpen: false, scrolled: false }"
        @scroll.window="scrolled = (window.pageYOffset > 10)"
        :class="{ 'shadow-md border-primary-light/40 bg-surface/98 dark:bg-gray-900/98': scrolled }"
        @keydown.escape.window="mobileOpen = false; aboutOpen = false">

    <x-frontend.container>
        <div class="flex items-center justify-between h-22 sm:h-24 w-full gap-4">

          
         <!-- Div 1: Brand / Logo -->
<div class="flex items-center shrink-0">
    <a href="{{ route('home') }}" class="flex items-center p-1 shrink-0">
        @if ($logoUrl)
            <img
                src="{{ $logoUrl }}"
                alt="{{ $brandName }}"
                class="h-20 sm:h-32 lg:h-36 w-auto max-w-[850px] object-contain"
            >
        @else
            <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl bg-primary flex items-center justify-center text-secondary-light font-bold text-lg sm:text-xl shadow-md hover:scale-105 transition-transform duration-200 shrink-0">
                {{ $locale === 'ar' ? 'أ' : 'A' }}
            </div>
        @endif
    </a>
</div>

            <!-- Div 2: Desktop Navigation Links -->
            <div class="hidden lg:flex items-center justify-center flex-1 font-navbar">
                <nav class="flex items-center gap-1.5 list-none" aria-label="{{ __('frontend.main_menu') }}">

                    <!-- Home -->
                    <x-frontend.nav-link :href="route('home')" :active="request()->routeIs('home')">
                        {{ __('frontend.home') }}
                    </x-frontend.nav-link>

                    <!-- About Dropdown -->
                    <div class="relative" 
                         x-data="{ 
                             open: false,
                             timeout: null,
                             clear() { if(this.timeout) clearTimeout(this.timeout) },
                             closeWithDelay() { 
                                 this.clear(); 
                                 this.timeout = setTimeout(() => { this.open = false }, 150); 
                             },
                             openImmediately() {
                                 this.clear();
                                 this.open = true;
                             }
                         }" 
                         @mouseenter="openImmediately()" 
                         @mouseleave="closeWithDelay()">
                        <button @click="open = !open"
                                type="button"
                                class="{{ $aboutActive ? 'text-primary dark:text-primary-light bg-primary/10 dark:bg-primary/20' : 'text-text-primary/80 dark:text-gray-300 hover:text-primary dark:hover:text-primary-light hover:bg-secondary-light/40 dark:hover:bg-gray-800/60' }} inline-flex items-center gap-1 px-2.5 py-2 text-[15px] font-semibold rounded-xl transition duration-150 ease-in-out focus:outline-none focus-visible:ring-2 focus-visible:ring-primary focus-visible:ring-offset-2"
                                :aria-expanded="open.toString()">
                            <span>{{ __('frontend.about_foundation') }}</span>
                            <svg class="w-4 h-4 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>

                        <div x-show="open"
                             x-cloak
                             x-transition:enter="transition ease-out duration-150"
                             x-transition:enter-start="opacity-0 translate-y-2"
                             x-transition:enter-end="opacity-100 translate-y-0"
                             x-transition:leave="transition ease-in duration-100"
                             x-transition:leave-start="opacity-100 translate-y-0"
                             x-transition:leave-end="opacity-0 translate-y-2"
                             @click.outside="open = false"
                             class="absolute top-full start-1/2 -translate-x-1/2 rtl:translate-x-1/2 pt-2 w-[calc(100vw-2rem)] max-w-[520px] lg:w-[520px] z-50">
                            <div class="bg-white dark:bg-gray-805 border border-primary-light/20 rounded-2xl shadow-2xl overflow-hidden grid grid-cols-12" role="menu">
                                <!-- Featured Panel Column -->
                                <div class="col-span-5 bg-gradient-to-br from-[#A38B54]/10 via-[#B49C6E]/5 to-transparent p-5 flex flex-col justify-between border-e border-primary-light/10">
                                    <div class="space-y-2">
                                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-[#A38B54]/10 text-[#A38B54] dark:bg-[#B49C6E]/20 dark:text-[#B49C6E]">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                        </span>
                                        <h4 class="text-sm font-semibold text-text-primary dark:text-gray-100">
                                            {{ __('frontend.about_foundation') }}
                                        </h4>
                                        <p class="text-xs text-text-primary/80 dark:text-gray-300 leading-relaxed">
                                            {{ app()->getLocale() === 'ar' ? 'تعرف على رؤيتنا وأهدافنا التنموية، وتشكيل مجلس الأمناء والفريق التنفيذي.' : 'Explore our vision, development goals, and the members leading our foundation.' }}
                                        </p>
                                    </div>
                                    <a href="{{ route('about.index') }}" class="inline-flex items-center gap-1 text-[11px] font-bold text-[#A38B54] dark:text-[#B49C6E] hover:underline pt-4">
                                        <span>{{ app()->getLocale() === 'ar' ? 'المزيد عنا' : 'More about us' }}</span>
                                        <svg class="w-3.5 h-3.5 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </a>
                                </div>

                                <!-- Links Column -->
                                <div class="col-span-7 p-3 flex flex-col gap-1 justify-center">
                                    <a href="{{ route('about.index') }}" class="group/item flex items-start gap-2.5 p-2 rounded-xl hover:bg-secondary-light/30 transition-all {{ request()->routeIs('about.index') ? 'bg-secondary-light/20' : '' }}">
                                        <span class="shrink-0 mt-0.5 w-6 h-6 rounded-md bg-[#A38B54]/5 text-[#A38B54] dark:bg-gray-700 dark:text-[#B49C6E] flex items-center justify-center group-hover/item:bg-[#A38B54] group-hover/item:text-white transition-colors">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                            </svg>
                                        </span>
                                        <div>
                                            <div class="text-sm font-semibold text-text-primary dark:text-gray-100 group-hover/item:text-[#A38B54] dark:group-hover/item:text-[#B49C6E] transition-colors">
                                                {{ __('frontend.about_us') }}
                                            </div>
                                            <div class="text-xs text-text-primary/70 dark:text-gray-300 mt-0.5 leading-normal">
                                                {{ app()->getLocale() === 'ar' ? 'نبذة تعريفية عن رؤيتنا وأهدافنا التنموية.' : 'A profile of our vision and identity.' }}
                                            </div>
                                        </div>
                                    </a>

                                    <a href="{{ route('about.board') }}" class="group/item flex items-start gap-2.5 p-2 rounded-xl hover:bg-secondary-light/30 transition-all {{ request()->routeIs('about.board') ? 'bg-secondary-light/20' : '' }}">
                                        <span class="shrink-0 mt-0.5 w-6 h-6 rounded-md bg-[#A38B54]/5 text-[#A38B54] dark:bg-gray-800 dark:text-[#B49C6E] flex items-center justify-center group-hover/item:bg-[#A38B54] group-hover/item:text-white transition-colors">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                                            </svg>
                                        </span>
                                        <div>
                                            <div class="text-sm font-semibold text-text-primary dark:text-gray-100 group-hover/item:text-[#A38B54] dark:group-hover/item:text-[#B49C6E] transition-colors">
                                                {{ __('frontend.board_of_directors') }}
                                            </div>
                                            <div class="text-xs text-text-primary/70 dark:text-gray-300 mt-0.5 leading-normal">
                                                {{ app()->getLocale() === 'ar' ? 'القيادة الإشرافية العليا لمؤسسة الأثر.' : 'The high supervisory leadership of Al-Athar.' }}
                                            </div>
                                        </div>
                                    </a>

                                                                   <a href="{{ route('about.executive-team') }}" class="group/item flex items-start gap-2.5 p-2 rounded-xl hover:bg-secondary-light/30 transition-all {{ request()->routeIs('about.executive-team') ? 'bg-secondary-light/20' : '' }}">
                                        <span class="shrink-0 mt-0.5 w-6 h-6 rounded-md bg-[#A38B54]/5 text-[#A38B54] dark:bg-gray-800 dark:text-[#B49C6E] flex items-center justify-center group-hover/item:bg-[#A38B54] group-hover/item:text-white transition-colors">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                            </svg>
                                        </span>
                                        <div>
                                            <div class="text-sm font-semibold text-text-primary dark:text-gray-100 group-hover/item:text-[#A38B54] dark:group-hover/item:text-[#B49C6E] transition-colors">
                                                {{ __('frontend.executive_team') }}
                                            </div>
                                            <div class="text-xs text-text-primary/70 dark:text-gray-300 mt-0.5 leading-normal">
                                                {{ app()->getLocale() === 'ar' ? 'الكفاءات التي تقود المبادرات اليومية.' : 'The team driving daily initiatives.' }}
                                            </div>
                                        </div>
                                    </a>

                                    <a href="{{ route('governance.index') }}" class="group/item flex items-start gap-2.5 p-2 rounded-xl hover:bg-secondary-light/30 transition-all {{ request()->routeIs('governance.*') ? 'bg-secondary-light/20' : '' }}">
                                        <span class="shrink-0 mt-0.5 w-6 h-6 rounded-md bg-[#A38B54]/5 text-[#A38B54] dark:bg-gray-800 dark:text-[#B49C6E] flex items-center justify-center group-hover/item:bg-[#A38B54] group-hover/item:text-white transition-colors">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138z"/>
                                            </svg>
                                        </span>
                                        <div>
                                            <div class="text-sm font-semibold text-text-primary dark:text-gray-100 group-hover/item:text-[#A38B54] dark:group-hover/item:text-[#B49C6E] transition-colors">
                                                {{ __('frontend.governance') }}
                                            </div>
                                            <div class="text-xs text-text-primary/70 dark:text-gray-300 mt-0.5 leading-normal">
                                                {{ app()->getLocale() === 'ar' ? 'شفافية التقارير والوثائق الحوكمية.' : 'Governance center, documents and compliance.' }}
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Programs -->
                    <x-frontend.nav-link :href="route('programs.index')" :active="request()->routeIs('programs.*')">
                        {{ __('frontend.programs') }}
                    </x-frontend.nav-link>

                    <!-- Projects -->
                    <x-frontend.nav-link :href="route('projects.index')" :active="request()->routeIs('projects.*')">
                        {{ __('frontend.projects') }}
                    </x-frontend.nav-link>

                    <!-- Content & Services Dropdown -->
                    <div class="relative" 
                         x-data="{ 
                             open: false,
                             timeout: null,
                             clear() { if(this.timeout) clearTimeout(this.timeout) },
                             closeWithDelay() { 
                                 this.clear(); 
                                 this.timeout = setTimeout(() => { this.open = false }, 150); 
                             },
                             openImmediately() {
                                 this.clear();
                                 this.open = true;
                             }
                         }" 
                         @mouseenter="openImmediately()" 
                         @mouseleave="closeWithDelay()">
                        <button @click="open = !open"
                                type="button"
                                class="{{ $servicesActive ? 'text-primary dark:text-primary-light bg-primary/10 dark:bg-primary/20' : 'text-text-primary/80 dark:text-gray-300 hover:text-primary dark:hover:text-primary-light hover:bg-secondary-light/40 dark:hover:bg-gray-800/60' }} inline-flex items-center gap-1 px-2.5 py-2 text-[15px] font-semibold rounded-xl transition duration-150 ease-in-out focus:outline-none focus-visible:ring-2 focus-visible:ring-primary focus-visible:ring-offset-2"
                                :aria-expanded="open.toString()">
                            <span>{{ __('frontend.content_services') }}</span>
                            <svg class="w-4 h-4 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>

                        <div x-show="open"
                             x-cloak
                             x-transition:enter="transition ease-out duration-150"
                             x-transition:enter-start="opacity-0 translate-y-2"
                             x-transition:enter-end="opacity-100 translate-y-0"
                             x-transition:leave="transition ease-in duration-100"
                             x-transition:leave-start="opacity-100 translate-y-0"
                             x-transition:leave-end="opacity-0 translate-y-2"
                             @click.outside="open = false"
                             class="absolute top-full start-1/2 -translate-x-1/2 rtl:translate-x-1/2 pt-2 w-[calc(100vw-2rem)] max-w-[660px] lg:w-[660px] z-50">
                            <div class="bg-white dark:bg-gray-800 border border-primary-light/20 rounded-2xl shadow-2xl overflow-hidden grid grid-cols-12" role="menu">
                                <!-- Featured Panel Column -->
                                <div class="col-span-4 bg-gradient-to-br from-[#A38B54]/10 via-[#B49C6E]/5 to-transparent p-5 flex flex-col justify-between border-e border-primary-light/10">
                                    <div class="space-y-2">
                                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-[#A38B54]/10 text-[#A38B54] dark:bg-[#B49C6E]/20 dark:text-[#B49C6E]">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                            </svg>
                                        </span>
                                        <h4 class="text-sm font-semibold text-text-primary dark:text-gray-100">
                                            {{ __('frontend.content_services') }}
                                        </h4>
                                        <p class="text-xs text-text-primary/80 dark:text-gray-300 leading-relaxed">
                                            {{ app()->getLocale() === 'ar' ? 'استعرض خدماتنا التنموية، مشاريعنا، والأنشطة التي نقدمها لتمكين وتطوير المجتمع.' : 'Browse our developmental services, projects, and activities designed to empower the community.' }}
                                        </p>
                                    </div>
                                    <a href="{{ route('content-services.index') }}" class="inline-flex items-center gap-1 text-[11px] font-bold text-[#A38B54] dark:text-[#B49C6E] hover:underline pt-4">
                                        <span>{{ app()->getLocale() === 'ar' ? 'كل المحتوى والخدمات' : 'All content & services' }}</span>
                                        <svg class="w-3.5 h-3.5 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </a>
                                </div>

                                <!-- Links Columns (2-column grid of links) -->
                                <div class="col-span-8 p-3 grid grid-cols-2 gap-1">
                                    <a href="{{ route('content-services.index') }}" class="group/item flex items-start gap-2.5 p-2 rounded-xl hover:bg-secondary-light/30 transition-all {{ request()->routeIs('content-services.index') ? 'bg-secondary-light/20' : '' }}">
                                        <span class="shrink-0 mt-0.5 w-6 h-6 rounded-md bg-[#A38B54]/5 text-[#A38B54] dark:bg-gray-800 dark:text-[#B49C6E] flex items-center justify-center group-hover/item:bg-[#A38B54] group-hover/item:text-white transition-colors">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                                            </svg>
                                        </span>
                                        <div>
                                            <div class="text-sm font-semibold text-text-primary dark:text-gray-100 group-hover/item:text-[#A38B54] dark:group-hover/item:text-[#B49C6E] transition-colors">
                                                {{ __('frontend.content_management') }}
                                            </div>
                                            <div class="text-xs text-text-primary/70 dark:text-gray-300 mt-0.5 leading-normal">
                                                {{ app()->getLocale() === 'ar' ? 'البوابة الموحدة لإدارة ونشر المحتوى والخدمات.' : 'Central portal to manage content.' }}
                                            </div>
                                        </div>
                                    </a>

                                    <a href="{{ route('services.index') }}" class="group/item flex items-start gap-2.5 p-2 rounded-xl hover:bg-secondary-light/30 transition-all {{ request()->routeIs('services.*') ? 'bg-secondary-light/20' : '' }}">
                                        <span class="shrink-0 mt-0.5 w-6 h-6 rounded-md bg-[#A38B54]/5 text-[#A38B54] dark:bg-gray-800 dark:text-[#B49C6E] flex items-center justify-center group-hover/item:bg-[#A38B54] group-hover/item:text-white transition-colors">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                            </svg>
                                        </span>
                                        <div>
                                            <div class="text-sm font-semibold text-text-primary dark:text-gray-100 group-hover/item:text-[#A38B54] dark:group-hover/item:text-[#B49C6E] transition-colors">
                                                {{ __('frontend.services') }}
                                            </div>
                                            <div class="text-xs text-text-primary/70 dark:text-gray-300 mt-0.5 leading-normal">
                                                {{ app()->getLocale() === 'ar' ? 'استكشف باقات خدماتنا الاستشارية والتنموية.' : 'Explore our consulting & development services.' }}
                                            </div>
                                        </div>
                                    </a>

                                    <a href="{{ route('activities.index') }}" class="group/item flex items-start gap-2.5 p-2 rounded-xl hover:bg-secondary-light/30 transition-all {{ request()->routeIs('activities.*') ? 'bg-secondary-light/20' : '' }}">
                                        <span class="shrink-0 mt-0.5 w-6 h-6 rounded-md bg-[#A38B54]/5 text-[#A38B54] dark:bg-gray-800 dark:text-[#B49C6E] flex items-center justify-center group-hover/item:bg-[#A38B54] group-hover/item:text-white transition-colors">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                            </svg>
                                        </span>
                                        <div>
                                            <div class="text-sm font-semibold text-text-primary dark:text-gray-100 group-hover/item:text-[#A38B54] dark:group-hover/item:text-[#B49C6E] transition-colors">
                                                {{ __('frontend.activities') }}
                                            </div>
                                            <div class="text-xs text-text-primary/70 dark:text-gray-300 mt-0.5 leading-normal">
                                                {{ app()->getLocale() === 'ar' ? 'الأنشطة الميدانية والجهود والبرامج التنموية.' : 'Field activities & community programs.' }}
                                            </div>
                                        </div>
                                    </a>

                                    <a href="{{ route('industries.index') }}" class="group/item flex items-start gap-2.5 p-2 rounded-xl hover:bg-secondary-light/30 transition-all {{ request()->routeIs('industries.*') ? 'bg-secondary-light/20' : '' }}">
                                        <span class="shrink-0 mt-0.5 w-6 h-6 rounded-md bg-[#A38B54]/5 text-[#A38B54] dark:bg-gray-800 dark:text-[#B49C6E] flex items-center justify-center group-hover/item:bg-[#A38B54] group-hover/item:text-white transition-colors">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                            </svg>
                                        </span>
                                        <div>
                                            <div class="text-sm font-semibold text-text-primary dark:text-gray-100 group-hover/item:text-[#A38B54] dark:group-hover/item:text-[#B49C6E] transition-colors">
                                                {{ __('frontend.industries') }}
                                            </div>
                                            <div class="text-xs text-text-primary/70 dark:text-gray-300 mt-0.5 leading-normal">
                                                {{ app()->getLocale() === 'ar' ? 'القطاعات والمجالات التنموية المستهدفة.' : 'Our targeted sectors & developmental domains.' }}
                                            </div>
                                        </div>
                                    </a>

                                    <a href="{{ route('solutions.index') }}" class="group/item flex items-start gap-2.5 p-2 rounded-xl hover:bg-secondary-light/30 transition-all {{ request()->routeIs('solutions.*') ? 'bg-secondary-light/20' : '' }} col-span-2">
                                        <span class="shrink-0 mt-0.5 w-6 h-6 rounded-md bg-[#A38B54]/5 text-[#A38B54] dark:bg-gray-800 dark:text-[#B49C6E] flex items-center justify-center group-hover/item:bg-[#A38B54] group-hover/item:text-white transition-colors">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                                            </svg>
                                        </span>
                                        <div>
                                            <div class="text-sm font-semibold text-text-primary dark:text-gray-100 group-hover/item:text-[#A38B54] dark:group-hover/item:text-[#B49C6E] transition-colors">
                                                {{ __('frontend.solutions') }}
                                            </div>
                                            <div class="text-xs text-text-primary/70 dark:text-gray-300 mt-0.5 leading-normal">
                                                {{ app()->getLocale() === 'ar' ? 'منهجيات وحلول مبتكرة ومستدامة للتحديات المجتمعية.' : 'Innovative methodologies to tackle community challenges.' }}
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Advertising Center Dropdown -->
                    <div class="relative" 
                         x-data="{ 
                             open: false,
                             timeout: null,
                             clear() { if(this.timeout) clearTimeout(this.timeout) },
                             closeWithDelay() { 
                                 this.clear(); 
                                 this.timeout = setTimeout(() => { this.open = false }, 150); 
                             },
                             openImmediately() {
                                 this.clear();
                                 this.open = true;
                             }
                         }" 
                         @mouseenter="openImmediately()" 
                         @mouseleave="closeWithDelay()">
                        <button @click="open = !open"
                                type="button"
                                class="{{ $advActive ? 'text-primary dark:text-primary-light bg-primary/10 dark:bg-primary/20' : 'text-text-primary/80 dark:text-gray-300 hover:text-primary dark:hover:text-primary-light hover:bg-secondary-light/40 dark:hover:bg-gray-800/60' }} inline-flex items-center gap-1 px-2.5 py-2 text-[15px] font-semibold rounded-xl transition duration-150 ease-in-out focus:outline-none focus-visible:ring-2 focus-visible:ring-primary focus-visible:ring-offset-2"
                                :aria-expanded="open.toString()">
                            <span>{{ __('frontend.advertising_center') }}</span>
                            <svg class="w-4 h-4 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>

                        <div x-show="open"
                             x-cloak
                             x-transition:enter="transition ease-out duration-150"
                             x-transition:enter-start="opacity-0 translate-y-2"
                             x-transition:enter-end="opacity-100 translate-y-0"
                             x-transition:leave="transition ease-in duration-100"
                             x-transition:leave-start="opacity-100 translate-y-0"
                             x-transition:leave-end="opacity-0 translate-y-2"
                             @click.outside="open = false"
                             class="absolute top-full start-1/2 -translate-x-1/2 rtl:translate-x-1/2 pt-2 w-[calc(100vw-2rem)] max-w-[600px] lg:w-[600px] z-50">
                            <div class="bg-white dark:bg-gray-800 border border-primary-light/20 rounded-2xl shadow-2xl overflow-hidden grid grid-cols-12" role="menu">
                                <!-- Featured Panel Column -->
                                <div class="col-span-4 bg-gradient-to-br from-[#A38B54]/10 via-[#B49C6E]/5 to-transparent p-5 flex flex-col justify-between border-e border-primary-light/10">
                                    <div class="space-y-2">
                                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-[#A38B54]/10 text-[#A38B54] dark:bg-[#B49C6E]/20 dark:text-[#B49C6E]">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 000-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/>
                                            </svg>
                                        </span>
                                        <h4 class="text-sm font-semibold text-text-primary dark:text-gray-100">
                                            {{ __('frontend.advertising_center') }}
                                        </h4>
                                        <p class="text-xs text-text-primary/80 dark:text-gray-300 leading-relaxed">
                                            {{ app()->getLocale() === 'ar' ? 'تصفح الأخبار الصحفية، التغطيات الإعلامية، ومكتبة الوسائط الخاصة بالمؤسسة.' : 'Follow press releases, media coverage, and the foundation\'s media library.' }}
                                        </p>
                                    </div>
                                    <a href="{{ route('advertising-center.index') }}" class="inline-flex items-center gap-1 text-[11px] font-bold text-[#A38B54] dark:text-[#B49C6E] hover:underline pt-4">
                                        <span>{{ app()->getLocale() === 'ar' ? 'المركز الإعلاني' : 'Advertising Center Home' }}</span>
                                        <svg class="w-3.5 h-3.5 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </a>
                                </div>

                                <!-- Links Columns -->
                                <div class="col-span-8 p-3 grid grid-cols-2 gap-1.5 items-center">
                                    <a href="{{ route('advertising-center.index') }}" class="group/item flex items-start gap-2.5 p-2 rounded-xl hover:bg-secondary-light/30 transition-all {{ request()->routeIs('advertising-center.index') ? 'bg-secondary-light/20' : '' }}">
                                        <span class="shrink-0 mt-0.5 w-6 h-6 rounded-md bg-[#A38B54]/5 text-[#A38B54] dark:bg-gray-800 dark:text-[#B49C6E] flex items-center justify-center group-hover/item:bg-[#A38B54] group-hover/item:text-white transition-colors">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4v16M17 4v16M3 8h4m10 0h4M3 12h18M3 16h4m10 0h4M4 20h16a1 1 0 001-1V5a1 1 0 00-1-1H4a1 1 0 00-1 1v14a1 1 0 001 1z"/>
                                            </svg>
                                        </span>
                                        <div>
                                            <div class="text-sm font-semibold text-text-primary dark:text-gray-100 group-hover/item:text-[#A38B54] dark:group-hover/item:text-[#B49C6E] transition-colors">
                                                {{ __('frontend.advertising_center') }}
                                            </div>
                                            <div class="text-xs text-text-primary/70 dark:text-gray-300 mt-0.5 leading-normal">
                                                {{ app()->getLocale() === 'ar' ? 'مكتبة وسائط المؤسسة.' : 'Media library hub.' }}
                                            </div>
                                        </div>
                                    </a>

                                    <a href="{{ route('news.index') }}" class="group/item flex items-start gap-2.5 p-2 rounded-xl hover:bg-secondary-light/30 transition-all {{ request()->routeIs('news.*') ? 'bg-secondary-light/20' : '' }}">
                                        <span class="shrink-0 mt-0.5 w-6 h-6 rounded-md bg-[#A38B54]/5 text-[#A38B54] dark:bg-gray-800 dark:text-[#B49C6E] flex items-center justify-center group-hover/item:bg-[#A38B54] group-hover/item:text-white transition-colors">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 4a2 2 0 012 2v5a2 2 0 01-2 2h-3m-6 0a3 3 0 00-3-3m0 0a3 3 0 013-3m0 3.5c0 .355.228.66.57.772l1.43.477a.895.895 0 001.03-.314l.872-1.309a.897.897 0 00-.094-1.12l-1.01-1.01a.895.895 0 00-1.12-.094l-1.309.872a.897.897 0 00-.314 1.03l.477 1.43c.112.342.417.57.772.57z"/>
                                            </svg>
                                        </span>
                                        <div>
                                            <div class="text-sm font-semibold text-text-primary dark:text-gray-100 group-hover/item:text-[#A38B54] dark:group-hover/item:text-[#B49C6E] transition-colors">
                                                {{ __('frontend.news') }}
                                            </div>
                                            <div class="text-xs text-text-primary/70 dark:text-gray-300 mt-0.5 leading-normal">
                                                {{ app()->getLocale() === 'ar' ? 'أحدث المستجدات والتقارير والقصص.' : 'Latest updates & articles.' }}
                                            </div>
                                        </div>
                                    </a>

                                    <a href="{{ route('events.index') }}" class="group/item flex items-start gap-2.5 p-2 rounded-xl hover:bg-secondary-light/30 transition-all {{ request()->routeIs('events.*') ? 'bg-secondary-light/20' : '' }}">
                                        <span class="shrink-0 mt-0.5 w-6 h-6 rounded-md bg-[#A38B54]/5 text-[#A38B54] dark:bg-gray-800 dark:text-[#B49C6E] flex items-center justify-center group-hover/item:bg-[#A38B54] group-hover/item:text-white transition-colors">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                        </span>
                                        <div>
                                            <div class="text-sm font-semibold text-text-primary dark:text-gray-100 group-hover/item:text-[#A38B54] dark:group-hover/item:text-[#B49C6E] transition-colors">
                                                {{ __('frontend.events') }}
                                            </div>
                                            <div class="text-xs text-text-primary/70 dark:text-gray-300 mt-0.5 leading-normal">
                                                {{ app()->getLocale() === 'ar' ? 'الورش والمؤتمرات والندوات المقبلة.' : 'Workshops, webinars & summits.' }}
                                            </div>
                                        </div>
                                    </a>

                                    <a href="{{ route('media-library.index') }}" class="group/item flex items-start gap-2.5 p-2 rounded-xl hover:bg-secondary-light/30 transition-all {{ request()->routeIs('media-library.*') ? 'bg-secondary-light/20' : '' }}">
                                        <span class="shrink-0 mt-0.5 w-6 h-6 rounded-md bg-[#A38B54]/5 text-[#A38B54] dark:bg-gray-800 dark:text-[#B49C6E] flex items-center justify-center group-hover/item:bg-[#A38B54] group-hover/item:text-white transition-colors">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                        </span>
                                        <div>
                                            <div class="text-sm font-semibold text-text-primary dark:text-gray-100 group-hover/item:text-[#A38B54] dark:group-hover/item:text-[#B49C6E] transition-colors">
                                                {{ __('frontend.media_library') }}
                                            </div>
                                            <div class="text-xs text-text-primary/70 dark:text-gray-300 mt-0.5 leading-normal">
                                                {{ app()->getLocale() === 'ar' ? 'الصور والفيديوهات والملفات الخاصة بالمؤسسة.' : 'Photos, videos & reports.' }}
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Governance, Surveys, Contact -->
                    @foreach ($navItems as $item)
                        <x-frontend.nav-link :href="route($item['route'])" :active="$item['active']">
                            {{ $item['label'] }}
                        </x-frontend.nav-link>
                    @endforeach

                    {{ $navLinks ?? '' }}
                </nav>
            </div>

            <!-- Div 3: Actions, Language Switcher & Mobile Menu Trigger -->
            <div class="flex items-center gap-3 shrink-0">
                <!-- Desktop Actions and Language Switcher -->
                <div class="hidden lg:flex items-center gap-2">
                    {{ $actions ?? '' }}

                    <!-- Language Switcher -->
                    @if (app()->getLocale() === 'ar')
                        <a href="{{ route('lang.switch', ['locale' => 'en', 'scope' => 'frontend']) }}"
                           class="inline-flex items-center gap-1 px-2.5 py-2 rounded-xl border border-primary-light/30 bg-white/60 dark:bg-gray-800/60 font-navbar text-[15px] font-semibold text-text-primary dark:text-gray-200 hover:bg-secondary-light/40 hover:border-primary/40 transition-all duration-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-primary focus-visible:ring-offset-2"
                           title="Switch to English"
                           aria-label="Switch language to English">
                            <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"/>
                            </svg>
                            <span>English</span>
                        </a>
                    @else
                        <a href="{{ route('lang.switch', ['locale' => 'ar', 'scope' => 'frontend']) }}"
                           class="inline-flex items-center gap-1 px-2.5 py-2 rounded-xl border border-primary-light/30 bg-white/60 dark:bg-gray-800/60 font-navbar text-[15px] font-semibold text-text-primary dark:text-gray-200 hover:bg-secondary-light/40 hover:border-primary/40 transition-all duration-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-primary focus-visible:ring-offset-2"
                           title="التحويل للعربية"
                           aria-label="تغيير اللغة إلى العربية">
                            <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"/>
                            </svg>
                            <span>العربية</span>
                        </a>
                    @endif
                </div>

                <!-- Mobile Hamburger Menu Button (visible on small/medium screens, hidden on large screens) -->
                <button @click="mobileOpen = !mobileOpen"
                        type="button"
                        class="lg:hidden p-2.5 rounded-xl text-text-primary dark:text-gray-200 hover:bg-secondary-light/40 focus:outline-none focus-visible:ring-2 focus-visible:ring-primary"
                        :aria-expanded="mobileOpen.toString()"
                        aria-label="{{ __('frontend.toggle_menu') }}">
                    <svg x-show="!mobileOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                    <svg x-show="mobileOpen" class="w-6 h-6" x-cloak fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>
    </x-frontend.container>

    <!-- Mobile Drawer -->
    <div x-show="mobileOpen"
         x-cloak
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 -translate-y-2"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 -translate-y-2"
         class="lg:hidden border-t border-primary-light/20 bg-surface/98 dark:bg-gray-900/98 backdrop-blur-md px-4 pt-3 pb-6 shadow-xl max-h-[calc(100vh-6rem)] overflow-y-auto">

        <nav class="flex flex-col gap-1 list-none font-navbar" aria-label="{{ __('frontend.main_menu') }}">
            <x-frontend.nav-link :href="route('home')" :active="request()->routeIs('home')" class="w-full justify-start py-3 text-[15px] font-semibold">
                {{ __('frontend.home') }}
            </x-frontend.nav-link>

            <!-- About accordion -->
            <div x-data="{ open: {{ $aboutActive ? 'true' : 'false' }} }">
                <button @click="open = !open" type="button"
                        class="w-full flex items-center justify-between px-3.5 py-3 text-[15px] font-semibold rounded-xl {{ $aboutActive ? 'text-primary font-bold bg-primary/10' : 'text-text-primary/80 dark:text-gray-300 hover:bg-secondary-light/40' }} hover:text-primary transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-primary">
                    <span>{{ __('frontend.about_foundation') }}</span>
                    <svg class="w-4 h-4 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div x-show="open" x-cloak class="ps-4 flex flex-col gap-1 mt-1 border-s-2 border-primary-light/30 ms-2">
                    <a href="{{ route('about.index') }}" class="px-3 py-2.5 text-[15px] font-semibold rounded-lg text-text-primary/80 dark:text-gray-300 hover:text-primary hover:bg-secondary-light/30 transition-colors {{ request()->routeIs('about.index') ? 'text-primary font-bold bg-secondary-light/30' : '' }}">{{ __('frontend.about_us') }}</a>
                    <a href="{{ route('about.board') }}" class="px-3 py-2.5 text-[15px] font-semibold rounded-lg text-text-primary/80 dark:text-gray-300 hover:text-primary hover:bg-secondary-light/30 transition-colors {{ request()->routeIs('about.board') ? 'text-primary font-bold bg-secondary-light/30' : '' }}">{{ __('frontend.board_of_directors') }}</a>
                    <a href="{{ route('about.executive-team') }}" class="px-3 py-2.5 text-[15px] font-semibold rounded-lg text-text-primary/80 dark:text-gray-300 hover:text-primary hover:bg-secondary-light/30 transition-colors {{ request()->routeIs('about.executive-team') ? 'text-primary font-bold bg-secondary-light/30' : '' }}">{{ __('frontend.executive_team') }}</a>
                    <a href="{{ route('governance.index') }}" class="px-3 py-2.5 text-[15px] font-semibold rounded-lg text-text-primary/80 dark:text-gray-300 hover:text-primary hover:bg-secondary-light/30 transition-colors {{ request()->routeIs('governance.*') ? 'text-primary font-bold bg-secondary-light/30' : '' }}">{{ __('frontend.governance') }}</a>
                </div>
            </div>

            <!-- Programs -->
            <x-frontend.nav-link :href="route('programs.index')" :active="request()->routeIs('programs.*')" class="w-full justify-start py-3 text-[15px] font-semibold">
                {{ __('frontend.programs') }}
            </x-frontend.nav-link>

            <!-- Projects -->
            <x-frontend.nav-link :href="route('projects.index')" :active="request()->routeIs('projects.*')" class="w-full justify-start py-3 text-[15px] font-semibold">
                {{ __('frontend.projects') }}
            </x-frontend.nav-link>

            <!-- Content & Services accordion -->
            <div x-data="{ open: {{ $servicesActive ? 'true' : 'false' }} }">
                <button @click="open = !open" type="button"
                        class="w-full flex items-center justify-between px-3.5 py-3 text-[15px] font-semibold rounded-xl {{ $servicesActive ? 'text-primary font-bold bg-primary/10' : 'text-text-primary/80 dark:text-gray-300 hover:bg-secondary-light/40' }} hover:text-primary transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-primary">
                    <span>{{ __('frontend.content_services') }}</span>
                    <svg class="w-4 h-4 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div x-show="open" x-cloak class="ps-4 flex flex-col gap-1 mt-1 border-s-2 border-primary-light/30 ms-2">
                    <a href="{{ route('content-services.index') }}" class="px-3 py-2.5 text-[15px] font-semibold rounded-lg text-text-primary/80 dark:text-gray-300 hover:text-primary hover:bg-secondary-light/30 transition-colors {{ request()->routeIs('content-services.index') ? 'text-primary font-bold bg-secondary-light/30' : '' }}">{{ __('frontend.content_management') }}</a>
                    <a href="{{ route('services.index') }}" class="px-3 py-2.5 text-[15px] font-semibold rounded-lg text-text-primary/80 dark:text-gray-300 hover:text-primary hover:bg-secondary-light/30 transition-colors {{ request()->routeIs('services.*') ? 'text-primary font-bold bg-secondary-light/30' : '' }}">{{ __('frontend.services') }}</a>
                    <a href="{{ route('activities.index') }}" class="px-3 py-2.5 text-[15px] font-semibold rounded-lg text-text-primary/80 dark:text-gray-300 hover:text-primary hover:bg-secondary-light/30 transition-colors {{ request()->routeIs('activities.*') ? 'text-primary font-bold bg-secondary-light/30' : '' }}">{{ __('frontend.activities') }}</a>
                    <a href="{{ route('industries.index') }}" class="px-3 py-2.5 text-[15px] font-semibold rounded-lg text-text-primary/80 dark:text-gray-300 hover:text-primary hover:bg-secondary-light/30 transition-colors {{ request()->routeIs('industries.*') ? 'text-primary font-bold bg-secondary-light/30' : '' }}">{{ __('frontend.industries') }}</a>
                    <a href="{{ route('solutions.index') }}" class="px-3 py-2.5 text-[15px] font-semibold rounded-lg text-text-primary/80 dark:text-gray-300 hover:text-primary hover:bg-secondary-light/30 transition-colors {{ request()->routeIs('solutions.*') ? 'text-primary font-bold bg-secondary-light/30' : '' }}">{{ __('frontend.solutions') }}</a>
                </div>
            </div>

            <!-- Advertising Center accordion -->
            <div x-data="{ open: {{ $advActive ? 'true' : 'false' }} }">
                <button @click="open = !open" type="button"
                        class="w-full flex items-center justify-between px-3.5 py-3 text-[15px] font-semibold rounded-xl {{ $advActive ? 'text-primary font-bold bg-primary/10' : 'text-text-primary/80 dark:text-gray-300 hover:bg-secondary-light/40' }} hover:text-primary transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-primary">
                    <span>{{ __('frontend.advertising_center') }}</span>
                    <svg class="w-4 h-4 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div x-show="open" x-cloak class="ps-4 flex flex-col gap-1 mt-1 border-s-2 border-primary-light/30 ms-2">
                    <a href="{{ route('advertising-center.index') }}" class="px-3 py-2.5 text-[15px] font-semibold rounded-lg text-text-primary/80 dark:text-gray-300 hover:text-primary hover:bg-secondary-light/30 transition-colors {{ request()->routeIs('advertising-center.index') ? 'text-primary font-bold bg-secondary-light/30' : '' }}">{{ app()->getLocale() === 'ar' ? 'المركز الإعلاني' : 'Advertising Center' }}</a>
                    <a href="{{ route('news.index') }}" class="px-3 py-2.5 text-[15px] font-semibold rounded-lg text-text-primary/80 dark:text-gray-300 hover:text-primary hover:bg-secondary-light/30 transition-colors {{ request()->routeIs('news.*') ? 'text-primary font-bold bg-secondary-light/30' : '' }}">{{ __('frontend.news') }}</a>
                    <a href="{{ route('events.index') }}" class="px-3 py-2.5 text-[15px] font-semibold rounded-lg text-text-primary/80 dark:text-gray-300 hover:text-primary hover:bg-secondary-light/30 transition-colors {{ request()->routeIs('events.*') ? 'text-primary font-bold bg-secondary-light/30' : '' }}">{{ __('frontend.events') }}</a>
                    <a href="{{ route('media-library.index') }}" class="px-3 py-2.5 text-[15px] font-semibold rounded-lg text-text-primary/80 dark:text-gray-300 hover:text-primary hover:bg-secondary-light/30 transition-colors {{ request()->routeIs('media-library.*') ? 'text-primary font-bold bg-secondary-light/30' : '' }}">{{ __('frontend.media_library') }}</a>
                </div>
            </div>

            @foreach ($navItems as $item)
                <x-frontend.nav-link :href="route($item['route'])" :active="$item['active']" class="w-full justify-start py-3 text-[15px] font-semibold">
                    {{ $item['label'] }}
                </x-frontend.nav-link>
            @endforeach

            {{ $mobileNavLinks ?? ($navLinks ?? '') }}
        </nav>

        <div class="pt-4 mt-4 border-t border-primary-light/20 flex flex-col gap-3">
            {{ $mobileActions ?? '' }}

            @if (app()->getLocale() === 'ar')
                <a href="{{ route('lang.switch', ['locale' => 'en', 'scope' => 'frontend']) }}"
                   class="w-full text-center flex items-center justify-center gap-2 px-4 py-3 rounded-xl border border-primary-light/30 bg-white/60 dark:bg-gray-800/60 font-semibold text-sm text-text-primary dark:text-gray-200 hover:bg-secondary-light/40 transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-primary">
                    <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"/>
                    </svg>
                    <span>English</span>
                </a>
            @else
                <a href="{{ route('lang.switch', ['locale' => 'ar', 'scope' => 'frontend']) }}"
                   class="w-full text-center flex items-center justify-center gap-2 px-4 py-3 rounded-xl border border-primary-light/30 bg-white/60 dark:bg-gray-800/60 font-semibold text-sm text-text-primary dark:text-gray-200 hover:bg-secondary-light/40 transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-primary">
                    <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"/>
                    </svg>
                    <span>العربية</span>
                </a>
            @endif
        </div>
    </div>

</header>
