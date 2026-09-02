@props([
    'brandName' => null,
    'siteDescription' => null,
    'copyrightText' => null,
])

@php
    $locale = app()->getLocale();
    $companyInfo = $companyInfo ?? [];
    $socialLinksSettings = $socialLinksSettings ?? [];

    // 1. Resolve Brand Name
    $resolvedBrandName = $brandName
        ?? ($companyInfo['name_' . $locale]
            ?? ($companyInfo['name_ar']
                ?? ($companyInfo['name_en']
                    ?? __('frontend.brand_name'))));

    // 2. Resolve Website Description
    $resolvedDescription = $siteDescription
        ?? ($companyInfo['description_' . $locale]
            ?? ($companyInfo['description_ar']
                ?? ($companyInfo['description_en']
                    ?? ($companyInfo['description']
                        ?? __('frontend.foundation_footer_desc')))));

    // 3. Resolve Copyright Text
    $resolvedCopyright = $copyrightText
        ?? ($companyInfo['copyright_' . $locale]
            ?? ($companyInfo['copyright_ar']
                ?? ($companyInfo['copyright_en']
                    ?? ($companyInfo['copyright']
                        ?? ('© ' . date('Y') . ' ' . $resolvedBrandName . '. ' . __('frontend.all_rights_reserved'))))));

    // 4. Resolve Address
    $resolvedAddress = $companyInfo['address_' . $locale]
        ?? ($companyInfo['address_ar']
            ?? ($companyInfo['address_en']
                ?? null));

    // 5. Resolve Email
    $resolvedEmail = $companyInfo['email'] ?? null;

    // 6. Resolve Phone Numbers
    $rawPhones = $companyInfo['phone_numbers'] ?? [];
    if (is_string($rawPhones)) {
        $rawPhones = [$rawPhones];
    }
    $phoneNumbers = array_values(array_filter((array)$rawPhones, fn($p) => is_string($p) && filled(trim($p))));

@endphp

<footer {{ $attributes->merge(['class' => 'w-full bg-text-primary text-background mt-auto transition-colors duration-200 relative']) }}>
    <!-- Upper Footer Area -->
    <div class="border-b border-accent/30 py-12 md:py-16">
        <x-frontend.container>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 md:gap-12 items-start">
                <!-- Column 1: Foundation Info (Logo, Name, Description) -->
                <div class="md:col-span-2 space-y-6 flex flex-col items-center md:items-start text-center md:text-start">
                    <div class="inline-block p-2 rounded-2xl transition-transform duration-300 hover:scale-102">
                        <x-application-logo class="h-20 sm:h-24 md:h-28 w-auto max-w-[280px] sm:max-w-[340px] object-contain drop-shadow-md" />
                    </div>
                    @if(filled($resolvedDescription))
                        <p class="text-sm sm:text-base leading-relaxed max-w-md" style="color: rgba(245,245,245,0.85)">
                            {{ $resolvedDescription }}
                        </p>
                    @endif
                </div>

                <!-- Column 2: Quick Links -->
                <div class="space-y-4">
                    <h3 class="text-base font-bold text-primary pb-1.5 border-b border-accent/30 inline-block">
                        {{ __('frontend.quick_links') }}
                    </h3>
                    <ul class="grid grid-cols-2 gap-x-4 gap-y-2.5 text-sm list-none" style="color: rgba(245,245,245,0.85)">
                        <li><a href="{{ route('home') }}" class="hover:text-secondary focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-secondary rounded transition-colors duration-200">{{ __('frontend.home') }}</a></li>
                        <li><a href="{{ route('about.index') }}" class="hover:text-secondary focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-secondary rounded transition-colors duration-200">{{ __('frontend.about_foundation') }}</a></li>
                        <li><a href="{{ route('about.board') }}" class="hover:text-secondary focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-secondary rounded transition-colors duration-200">{{ __('frontend.board_of_directors') }}</a></li>
                        <li><a href="{{ route('about.executive-team') }}" class="hover:text-secondary focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-secondary rounded transition-colors duration-200">{{ __('frontend.executive_team') }}</a></li>
                        <li><a href="{{ route('about.organizational-structure') }}" class="hover:text-secondary focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-secondary rounded transition-colors duration-200">{{ __('frontend.organizational_structure') }}</a></li>
                        <li><a href="{{ route('services.index') }}" class="hover:text-secondary focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-secondary rounded transition-colors duration-200">{{ __('frontend.services') }}</a></li>
                        <li><a href="{{ route('industries.index') }}" class="hover:text-secondary focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-secondary rounded transition-colors duration-200">{{ __('frontend.industries') }}</a></li>
                        <li><a href="{{ route('news.index') }}" class="hover:text-secondary focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-secondary rounded transition-colors duration-200">{{ __('frontend.news') }}</a></li>
                        <li><a href="{{ route('surveys.index') }}" class="hover:text-secondary focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-secondary rounded transition-colors duration-200">{{ __('frontend.surveys') }}</a></li>
                        <li><a href="{{ route('contact.index') }}" class="hover:text-secondary focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-secondary rounded transition-colors duration-200">{{ __('frontend.contact') }}</a></li>
                    </ul>                   
                        
                </div>

                <!-- Column 3: Contact Info & Social Media Links -->
                <div class="space-y-4">
                    <h3 class="text-base font-bold text-primary pb-1.5 border-b border-accent/30 inline-block">
                        {{ __('frontend.contact_us') }}
                    </h3>

                    <div class="space-y-3 text-sm" style="color: rgba(245,245,245,0.85)">
                        @if(filled($resolvedAddress))
                            <div class="flex items-start gap-2.5">
                                <svg class="w-4 h-4 mt-0.5 shrink-0 text-primary fill-none stroke-current stroke-2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                <span>{{ $resolvedAddress }}</span>
                            </div>
                        @endif

                        @if(filled($resolvedEmail))
                            <div class="flex items-center gap-2.5">
                                <svg class="w-4 h-4 shrink-0 text-primary fill-none stroke-current stroke-2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                <a href="mailto:{{ $resolvedEmail }}" class="hover:text-secondary focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-secondary rounded transition-colors duration-200" style="color: rgba(245,245,245,0.85)">{{ $resolvedEmail }}</a>
                            </div>
                        @endif

                        @if(!empty($phoneNumbers))
                            @foreach($phoneNumbers as $phone)
                                <div class="flex items-center gap-2.5">
                                    <svg class="w-4 h-4 shrink-0 text-primary fill-none stroke-current stroke-2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                    <a href="tel:{{ preg_replace('/[^\d+]/', '', $phone) }}" class="hover:text-secondary focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-secondary rounded transition-colors duration-200" style="color: rgba(245,245,245,0.85)" dir="ltr">{{ $phone }}</a>
                                </div>
                            @endforeach
                        @endif
                    </div>

                    <!-- Social Media Links -->
                    <div class="flex flex-wrap items-center gap-3 pt-3">
                        @if(isset($socialLinks) && filled((string)$socialLinks))
                            {{ $socialLinks }}
                        @elseif(!empty($socialLinksSettings))
                            @foreach($socialLinksSettings as $platform => $url)
                                @if(filled($url))
                                    <a href="{{ $url }}" target="_blank" rel="noopener noreferrer" class="w-9 h-9 rounded-full border border-primary bg-transparent hover:bg-primary text-primary hover:text-background hover:scale-105 flex items-center justify-center transition-all duration-200 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary" title="{{ ucfirst($platform) }}">
                                        <span class="sr-only">{{ $platform }}</span>
                                        @switch(strtolower($platform))
                                            @case('facebook')
                                                <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                                                @break
                                            @case('twitter')
                                            @case('x')
                                                <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                                                @break
                                            @case('instagram')
                                                <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                                                @break
                                            @case('linkedin')
                                                <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.239-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/></svg>
                                                @break
                                            @case('youtube')
                                                <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                                                @break
                                            @case('whatsapp')
                                                <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981z"/></svg>
                                                @break
                                            @case('telegram')
                                                <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M12 0C5.37 0 0 5.37 0 12s5.37 12 12 12 12-5.37 12-12S18.63 0 12 0zm5.562 8.161c-.18 1.897-.962 6.502-1.359 8.627-.168.9-.5 1.201-.82 1.23-.697.064-1.226-.461-1.901-.903-1.056-.692-1.653-1.123-2.678-1.799-1.185-.781-.417-1.21.258-1.911.177-.184 3.247-2.977 3.307-3.23.007-.032.014-.15-.056-.212s-.174-.041-.249-.024c-.106.024-1.793 1.14-5.061 3.345-.48.33-.913.49-1.302.48-.428-.008-1.252-.241-1.865-.44-.752-.245-1.349-.374-1.297-.789.027-.216.325-.437.893-.663 3.498-1.524 5.831-2.529 6.998-3.014 3.332-1.386 4.025-1.627 4.476-1.635.099-.002.321.023.465.14.119.098.152.228.166.331.015.109.034.356.019.55z"/></svg>
                                                @break
                                            @default
                                                <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 17.93c-3.95-.49-7-3.85-7-7.93 0-.62.08-1.21.21-1.79L9 15v1c0 1.1.9 2 2 2v1.93zm6.9-2.54c-.26-.81-1-1.39-1.9-1.39h-1v-3c0-.55-.45-1-1-1H8v-2h2c.55 0 1-.45 1-1V7h2c1.1 0 2-.9 2-2v-.41c2.93 1.19 5 4.06 5 7.41 0 2.08-.8 3.97-2.1 5.39z"/></svg>
                                        @endswitch
                                    </a>
                                @endif
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
        </x-frontend.container>
    </div>

    <!-- Bottom Copyright Area -->
    <div class="py-5 text-xs border-t border-accent/25" style="background-color: #372828">
        <x-frontend.container class="flex flex-col sm:flex-row items-center justify-between gap-4 text-center sm:text-start">
            <p style="color: rgba(245,245,245,0.70)">
                {{ $resolvedCopyright }}
            </p>
            <div class="flex items-center gap-4">
                {{ $footerBottom ?? '' }}
            </div>
        </x-frontend.container>
    </div>
</footer>


