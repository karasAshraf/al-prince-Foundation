<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
      dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}"
      class="h-full scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @php
        $locale       = app()->getLocale();
        $appName      = $companyInfo["name_{$locale}"] ?? __('frontend.brand_name');
        
        // 1. Resolve SEO Meta relation
        $resolvedSeo = $seoMeta ?? (isset($model) && method_exists($model, 'seoMeta') ? $model->seoMeta : null);
        
        // 2. Resolve Title
        $seoTitle = $resolvedSeo ? ($locale === 'ar' ? $resolvedSeo->meta_title_ar : $resolvedSeo->meta_title_en) : null;
        $modelTitle = isset($model) ? ($model->{"title_{$locale}"} ?? $model->{"name_{$locale}"} ?? null) : null;
        $finalTitle = $seoTitle ?: $title ?: $modelTitle;
        $pageTitle  = $finalTitle ? "{$finalTitle} | {$appName}" : $appName;
        
        // 3. Resolve Description
        $seoDesc = $resolvedSeo ? ($locale === 'ar' ? $resolvedSeo->meta_description_ar : $resolvedSeo->meta_description_en) : null;
        $modelDesc = null;
        if (isset($model)) {
            $modelDesc = $model->{"summary_{$locale}"} ?? $model->{"excerpt_{$locale}"} ?? $model->{"description_{$locale}"} ?? $model->{"content_{$locale}"} ?? null;
            if ($modelDesc) {
                $modelDesc = \Illuminate\Support\Str::limit(strip_tags($modelDesc), 160);
            }
        }
        $globalDesc = $companyInfo["description_{$locale}"] ?? '';
        $pageDesc = $seoDesc ?: $metaDescription ?: $modelDesc ?: $globalDesc;
        
        // 4. Resolve Keywords
        $seoKeywords = $resolvedSeo ? $resolvedSeo->meta_keywords : null;
        $globalKeywords = 'مؤسسة, الأمير, عبد الرحمن, برامج, مشاريع, Prince, Abdulrahman, Foundation, Development';
        $pageKeywords = $seoKeywords ?: $metaKeywords ?: $globalKeywords;
        
        // 5. Resolve OG Image
        $seoOgImage = $resolvedSeo ? $resolvedSeo->og_image : null;
        $modelImage = null;
        if (isset($model) && isset($model->image)) {
            $modelImage = \App\Helpers\MediaHelper::url($model, $model->getTable() . '_images', 'image');
        }
        $globalLogo = !empty($companyInfo['logo']) ? $companyInfo['logo'] : null;
        $pageOgImage = $seoOgImage ?: $ogImage ?: $modelImage ?: $globalLogo;
        
        // 6. Resolve Canonical URL
        $seoCanonical = $resolvedSeo ? $resolvedSeo->canonical_url : null;
        $pageCanonical = $seoCanonical ?: $canonicalUrl ?: request()->url();

        $currentPlacement = null;
        if (\Route::currentRouteName()) {
            $routeName = \Route::currentRouteName();
            $segment = explode('.', $routeName)[0];
            
            // Only allow on listing page/home (routeName is 'home' or ends with '.index')
            if ($routeName === 'home' || str_ends_with($routeName, '.index')) {
                $placements = array_keys(\App\Helpers\NavigationHelper::getPlacements());
                if (in_array($segment, $placements)) {
                    $currentPlacement = $segment;
                }
            }
        }
        
        $hasActiveSlides = false;
        if (!isset($hero) && $currentPlacement) {
            $hasActiveSlides = \App\Models\HeroSlide::active()
                ->where('placement', $currentPlacement)
                ->exists();
        }
    @endphp

    <title>{{ $pageTitle }}</title>
    @if ($pageDesc)
        <meta name="description" content="{{ $pageDesc }}">
    @endif
    @if ($pageKeywords)
        <meta name="keywords" content="{{ $pageKeywords }}">
    @endif
    <link rel="canonical" href="{{ $pageCanonical }}">
    <meta property="og:title" content="{{ $pageTitle }}">
    <meta property="og:type" content="website">
    @if ($pageDesc)
        <meta property="og:description" content="{{ $pageDesc }}">
    @endif
    @if ($pageOgImage)
        <meta property="og:image" content="{{ $pageOgImage }}">
    @endif


    <!-- Google Fonts Cairo -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Vite Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Native Speculation Rules API for Same-Origin Frontend Prefetching & Prerendering -->
    <script type="speculationrules">
    {
      "prerender": [
        {
          "source": "document",
          "where": {
            "and": [
              {
                "or": [
                  { "href_matches": "/" },
                  { "href_matches": "/about*" },
                  { "href_matches": "/services*" },
                  { "href_matches": "/programs*" },
                  { "href_matches": "/projects*" },
                  { "href_matches": "/news*" },
                  { "href_matches": "/governance*" },
                  { "href_matches": "/activities*" },
                  { "href_matches": "/industries*" },
                  { "href_matches": "/solutions*" },
                  { "href_matches": "/events*" },
                  { "href_matches": "/media-library*" },
                  { "href_matches": "/advertising-center*" },
                  { "href_matches": "/content-services*" }
                ]
              },
              { "not": { "href_matches": "/dashboard*" } },
              { "not": { "href_matches": "/logout" } },
              { "not": { "href_matches": "/login" } },
              { "not": { "href_matches": "/register" } }
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
              { "href_matches": "/*" },
              { "not": { "href_matches": "/dashboard*" } },
              { "not": { "href_matches": "/logout" } },
              { "not": { "href_matches": "/login" } },
              { "not": { "href_matches": "/register" } }
            ]
          },
          "eagerness": "moderate"
        }
      ]
    }
    </script>

    <!-- Preload / Additional Head Stack -->
    @stack('preload')
    @stack('styles')
</head>
<body class="min-h-screen flex flex-col bg-gray-100 dark:bg-gray-950 text-text-primary font-sans antialiased selection:bg-secondary-light selection:text-text-primary transition-colors duration-200">
    
    <!-- Widescreen Centered Wrapper -->
    <div class="w-full mx-auto flex flex-col min-h-screen bg-surface dark:bg-gray-900 shadow-2xl relative overflow-x-clip">
        
        <!-- Navbar (Full Width) -->
        <x-frontend.navbar :brand-name="$brandName ?? null">
            @if(isset($navLinks))
                <x-slot:navLinks>
                    {{ $navLinks }}
                </x-slot:navLinks>
            @endif

            @if(isset($actions))
                <x-slot:actions>
                    {{ $actions }}
                </x-slot:actions>
            @endif

            @if(isset($mobileNavLinks))
                <x-slot:mobileNavLinks>
                    {{ $mobileNavLinks }}
                </x-slot:mobileNavLinks>
            @endif

            @if(isset($mobileActions))
                <x-slot:mobileActions>
                    {{ $mobileActions }}
                </x-slot:mobileActions>
            @endif
        </x-frontend.navbar>

        <!-- Full-Width Hero Slot (outside container, zero padding) -->
        @if(isset($hero))
            <div class="w-full">
                {{ $hero }}
            </div>
        @elseif($hasActiveSlides && $currentPlacement)
            <div class="w-full">
                <x-hero-slider :variant="$currentPlacement === 'home' ? 'home' : 'inner'" :placement="$currentPlacement" />
            </div>
        @endif

        <!-- Main Content Container -->
        @if ($slot->isNotEmpty())
            @php
                $mainClasses = 'flex-grow';
                if (isset($hero) || $hasActiveSlides) {
                    if ($currentPlacement === 'home') {
                        $mainClasses .= '';
                    } else {
                        $mainClasses .= ' pt-6 md:pt-8 pb-8 md:pb-12';
                    }
                } else {
                    $mainClasses .= ' py-8 md:py-12';
                }
            @endphp
            <main class="{{ $mainClasses }}">
                <x-frontend.container :fluid="$containerFluid ?? false">
                    {{ $slot }}
                </x-frontend.container>
            </main>
        @endif

        <!-- Footer (Full Width) -->
        <x-frontend.footer :brand-name="$brandName ?? null">
            @if(isset($footerLinks))
                <x-slot:footerLinks>
                    {{ $footerLinks }}
                </x-slot:footerLinks>
            @endif

            @if(isset($socialLinks))
                <x-slot:socialLinks>
                    {{ $socialLinks }}
                </x-slot:socialLinks>
            @endif

            @if(isset($footerBottom))
                <x-slot:footerBottom>
                    {{ $footerBottom }}
                </x-slot:footerBottom>
            @endif
        </x-frontend.footer>

        <!-- Floating Back to Top Button -->
        <div x-data="{ show: false }"
             x-init="window.addEventListener('scroll', () => { show = window.pageYOffset > 400 })"
             class="fixed bottom-6 end-6 z-50">
            <button x-show="show"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4 scale-90"
                    x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                    x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                    x-transition:leave-end="opacity-0 translate-y-4 scale-90"
                    @click="window.scrollTo({top: 0, behavior: 'smooth'})"
                    type="button"
                    class="flex items-center justify-center w-12 h-12 rounded-full bg-[#AC8321] text-white shadow-xl hover:bg-[#B8974F] hover:scale-110 active:scale-95 transition-all duration-300 focus:outline-none focus-visible:ring-2 focus-visible:ring-primary-light border border-[#D5D3CE]/20"
                    aria-label="{{ __('frontend.back_to_top') ?: 'Back to Top' }}">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 10l7-7m0 0l7 7m-7-7v18"/>
                </svg>
            </button>
        </div>

    </div>

    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.min.js" defer></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (window.lucide) lucide.createIcons();
        });
    </script>

    <!-- Additional Scripts Stack -->
    @stack('scripts')
</body>
</html>
