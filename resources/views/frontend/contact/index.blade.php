<x-frontend-layout title="{{ __('frontend.contact') }}">

    <!-- Page Header -->
    <div class="text-center mb-12 sm:mb-16">
        <x-frontend.badge variant="secondary">{{ __('frontend.we_are_happy_to_connect') }}</x-frontend.badge>
        <h1 class="text-3xl sm:text-4xl font-bold text-text-primary mt-3 leading-tight font-sans">
            {{ __('frontend.contact') }}
        </h1>
        <p class="mt-4 text-text-primary/75 max-w-xl mx-auto text-sm sm:text-base leading-relaxed">
            {{ __('frontend.contact_header_desc') }}
        </p>
    </div>

    <!-- Balanced Two-Column Section on Desktop, Stacks on Mobile -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 max-w-6xl mx-auto items-stretch">

        <!-- Contact Form Side (Column 1 - 7/12 width) -->
        <div class="lg:col-span-7 flex flex-col justify-between">
            @if (session('success'))
                <div class="mb-6 p-4 rounded-2xl bg-[#A38B54]/10 border border-[#A38B54]/30 text-[#A38B54] font-semibold text-center text-sm shadow-sm" role="alert">
                    ✓ {{ __(session('success')) }}
                </div>
            @endif

            <form action="{{ route('contact.store') }}" method="POST" class="bg-white border border-[#B49C6E]/20 rounded-3xl p-6 sm:p-10 space-y-6 shadow-sm flex-1 flex flex-col justify-between">
                @csrf

                <!-- Honeypot -->
                <input type="text" name="website" class="hidden" tabindex="-1" autocomplete="off" aria-hidden="true">

                <div class="space-y-6">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <!-- Name -->
                        <div class="space-y-2">
                            <label for="name" class="block text-sm font-semibold text-text-primary">
                                {{ __('frontend.full_name') }} <span class="text-red-500" aria-hidden="true">*</span>
                            </label>
                            <input type="text" name="name" id="name" required value="{{ old('name') }}"
                                   placeholder="{{ __('frontend.enter_full_name') }}"
                                   class="w-full px-4 py-3 rounded-xl border border-[#B49C6E]/30 bg-[#EAEAE9]/50 focus:outline-none focus:ring-2 focus:ring-[#A38B54] focus:border-transparent text-sm transition-all">
                            @error('name')
                                <p class="text-xs text-red-500 mt-1" role="alert">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div class="space-y-2">
                            <label for="email" class="block text-sm font-semibold text-text-primary">
                                {{ __('frontend.email_address') }} <span class="text-red-500" aria-hidden="true">*</span>
                            </label>
                            <input type="email" name="email" id="email" required value="{{ old('email') }}"
                                   placeholder="example@domain.com"
                                   class="w-full px-4 py-3 rounded-xl border border-[#B49C6E]/30 bg-[#EAEAE9]/50 focus:outline-none focus:ring-2 focus:ring-[#A38B54] focus:border-transparent text-sm transition-all">
                            @error('email')
                                <p class="text-xs text-red-500 mt-1" role="alert">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <!-- Phone -->
                        <div class="space-y-2">
                            <label for="phone" class="block text-sm font-semibold text-text-primary">
                                {{ __('frontend.phone_number') }}
                            </label>
                            <input type="text" name="phone" id="phone" value="{{ old('phone') }}"
                                   placeholder="0500000000"
                                   class="w-full px-4 py-3 rounded-xl border border-[#B49C6E]/30 bg-[#EAEAE9]/50 focus:outline-none focus:ring-2 focus:ring-[#A38B54] focus:border-transparent text-sm transition-all">
                            @error('phone')
                                <p class="text-xs text-red-500 mt-1" role="alert">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Message Type -->
                        <div class="space-y-2">
                            <label for="type" class="block text-sm font-semibold text-text-primary">
                                {{ __('frontend.message_type') }}
                            </label>
                            <select name="type" id="type"
                                    class="w-full px-4 py-3 rounded-xl border border-[#B49C6E]/30 bg-[#EAEAE9]/50 focus:outline-none focus:ring-2 focus:ring-[#A38B54] focus:border-transparent text-sm transition-all">
                                <option value="general" {{ old('type') === 'general' ? 'selected' : '' }}>{{ __('frontend.general_inquiry') }}</option>
                                <option value="complaint" {{ old('type') === 'complaint' ? 'selected' : '' }}>{{ __('frontend.complaint_suggestion') }}</option>
                            </select>
                            @error('type')
                                <p class="text-xs text-red-500 mt-1" role="alert">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Subject -->
                    <div class="space-y-2">
                        <label for="subject" class="block text-sm font-semibold text-text-primary">
                            {{ __('frontend.subject') }}
                        </label>
                        <input type="text" name="subject" id="subject" value="{{ old('subject') }}"
                               placeholder="{{ __('frontend.enter_subject') }}"
                               class="w-full px-4 py-3 rounded-xl border border-[#B49C6E]/30 bg-[#EAEAE9]/50 focus:outline-none focus:ring-2 focus:ring-[#A38B54] focus:border-transparent text-sm transition-all">
                        @error('subject')
                            <p class="text-xs text-red-500 mt-1" role="alert">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Message -->
                    <div class="space-y-2">
                        <label for="message" class="block text-sm font-semibold text-text-primary">
                            {{ __('frontend.message') }} <span class="text-red-500" aria-hidden="true">*</span>
                        </label>
                        <textarea name="message" id="message" rows="5" required
                                  placeholder="{{ __('frontend.write_message_here') }}"
                                  class="w-full px-4 py-3 rounded-xl border border-[#B49C6E]/30 bg-[#EAEAE9]/50 focus:outline-none focus:ring-2 focus:ring-[#A38B54] focus:border-transparent text-sm transition-all">{{ old('message') }}</textarea>
                        @error('message')
                            <p class="text-xs text-red-500 mt-1" role="alert">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="pt-6 border-t border-[#B49C6E]/10">
                    <x-frontend.button type="submit" variant="primary" size="lg" class="w-full sm:w-auto px-10">
                        {{ __('frontend.send_message') }}
                    </x-frontend.button>
                </div>
            </form>
        </div>

        <!-- Live Map & Info Card Side (Column 2 - 5/12 width) -->
        <div class="lg:col-span-5 flex flex-col">
            <div class="bg-white border border-[#B49C6E]/20 rounded-3xl p-6 sm:p-8 shadow-sm flex flex-col h-full justify-between gap-6">
                
                <!-- Map Header & Name -->
                <div>
                    <h2 class="text-lg font-bold text-text-primary flex items-center gap-2">
                        <span aria-hidden="true">📍</span>
                        {{ __('frontend.our_location') }}
                    </h2>
                    <p class="text-xs text-text-primary/60 mt-1 font-semibold">
                        {{ $locationName }}
                    </p>
                </div>

                <!-- Google Map Container -->
                <div class="relative w-full h-72 sm:h-80 md:h-96 lg:flex-1 rounded-2xl overflow-hidden border border-[#B49C6E]/20 shadow-inner bg-gray-50">
                    <iframe
                        src="{{ $mapIframeUrl }}"
                        class="absolute inset-0 w-full h-full border-0"
                        allowfullscreen=""
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade"
                        title="Foundation location on Google Maps"
                    ></iframe>
                </div>

                <!-- Contact & Address Details Section below map -->
                <div class="space-y-4 text-sm border-t border-[#B49C6E]/10 pt-4">
                    <!-- Address text -->
                    <div class="flex items-start gap-3">
                        <span class="text-lg shrink-0 mt-0.5" aria-hidden="true">🏢</span>
                        <div>
                            <p class="text-xs font-bold text-text-primary/50 uppercase tracking-wider">{{ __('frontend.address') }}</p>
                            <p class="font-medium text-text-primary mt-0.5 leading-relaxed">{{ $siteAddress }}</p>
                        </div>
                    </div>

                    <!-- Phone links -->
                    <div class="flex items-start gap-3">
                        <span class="text-lg shrink-0 mt-0.5" aria-hidden="true">📞</span>
                        <div>
                            <p class="text-xs font-bold text-text-primary/50 uppercase tracking-wider">{{ __('frontend.phone_whatsapp') }}</p>
                            <a href="tel:{{ str_replace(' ', '', $sitePhone) }}" class="inline-block font-medium text-[#A38B54] hover:underline mt-0.5" dir="ltr">
                                {{ $sitePhone }}
                            </a>
                        </div>
                    </div>

                    <!-- Email link -->
                    <div class="flex items-start gap-3">
                        <span class="text-lg shrink-0 mt-0.5" aria-hidden="true">✉️</span>
                        <div>
                            <p class="text-xs font-bold text-text-primary/50 uppercase tracking-wider">{{ __('frontend.email') }}</p>
                            <a href="mailto:{{ $siteEmail }}" class="inline-block font-medium text-[#A38B54] hover:underline mt-0.5">
                                {{ $siteEmail }}
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Google Maps External Button -->
                @if($googleMapsUrl)
                    <div class="pt-2 border-t border-[#B49C6E]/10">
                        <a href="{{ $googleMapsUrl }}" target="_blank" rel="noopener noreferrer" 
                           class="w-full inline-flex items-center justify-center gap-2 rounded-xl bg-[#A38B54] px-5 py-3 text-xs font-bold text-[#EAEAE9] hover:bg-[#3D342A] shadow-sm transition-all"
                           aria-label="{{ __('frontend.open_in_google_maps') }}">
                            <span>🗺️</span>
                            <span>{{ __('frontend.open_in_google_maps') }}</span>
                        </a>
                    </div>
                @endif

            </div>
        </div>

    </div>

</x-frontend-layout>
