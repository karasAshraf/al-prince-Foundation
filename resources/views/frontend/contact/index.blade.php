{{--
    Contact Us page — /contact
    ─────────────────────────────────────────────────────────────────────────
    Hero: rendered automatically by the frontend layout's placement-detection
    for the `contact.index` route (layouts/frontend.blade.php lines 53-73).
    The `contact` placement is now registered in NavigationHelper::getPlacements().
    Create/manage hero slides for this page in Dashboard → Hero Slides → placement = "contact".
    If no slides exist, the hero component shows its built-in fallback gradient.

    The redundant static header block (badge + h1 + p) has been removed —
    its messaging responsibility lives entirely in the hero slide.
--}}
<x-frontend-layout title="{{ __('frontend.contact') }}">

    {{-- ── Section intro heading ─────────────────────────────────────────── --}}
    <div class="mb-10"
         x-data="{ inView: false }"
         x-intersect.once="inView = true">
        <div class="flex items-center gap-3 mb-1"
             :class="inView ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-4'"
             class="transition-all duration-500 ease-out">
            <span class="w-1 h-8 rounded-full bg-primary shrink-0" aria-hidden="true"></span>
            <h2 class="text-2xl sm:text-3xl font-extrabold text-text-primary tracking-tight">
                {{ __('frontend.contact') }}
            </h2>
        </div>
        <p class="text-sm text-text-primary/60 ms-4 font-sans"
           :class="inView ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-4'"
           class="transition-all duration-500 ease-out delay-100">
            {{ __('frontend.contact_header_desc') }}
        </p>
    </div>

    {{-- ── Two-Column Layout ─────────────────────────────────────────────── --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 max-w-6xl mx-auto items-stretch">

        {{-- ── Contact Form Column (7/12) ────────────────────────────────── --}}
        <div class="lg:col-span-7 flex flex-col">

            {{-- Success flash --}}
            @if (session('success'))
                <div class="mb-6 p-4 rounded-2xl bg-primary/10 border border-primary/25 text-primary
                            font-semibold text-center text-sm shadow-sm"
                     role="alert">
                    ✓ {{ __(session('success')) }}
                </div>
            @endif

            <form action="{{ route('contact.store') }}" method="POST"
                  class="flex-1 flex flex-col space-y-7"
                  x-data="{ inView: false }"
                  x-intersect.once="inView = true"
                  :class="inView ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-6'"
                  style="transition: opacity 0.7s ease-out, transform 0.7s ease-out;">
                @csrf

                {{-- Honeypot --}}
                <input type="text" name="website" class="hidden" tabindex="-1" autocomplete="off" aria-hidden="true">

                {{-- ── Name + Email row ─────────────────────────────────── --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">

                    {{-- Name --}}
                    <div class="space-y-2">
                        <label for="name" class="block text-sm font-bold text-text-primary">
                            {{ __('frontend.full_name') }}
                            <span class="text-primary ms-0.5" aria-hidden="true">*</span>
                        </label>
                        <input type="text" name="name" id="name" required
                               value="{{ old('name') }}"
                               placeholder="{{ __('frontend.enter_full_name') }}"
                               class="w-full bg-transparent border-0 border-b-2 border-secondary/60
                                      focus:border-primary focus:outline-none focus:ring-0
                                      py-2.5 text-sm text-text-primary
                                      placeholder:text-text-primary/35
                                      transition-colors duration-200">
                        @error('name')
                            <p class="text-xs text-red-500 mt-1" role="alert">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Email --}}
                    <div class="space-y-2">
                        <label for="email" class="block text-sm font-bold text-text-primary">
                            {{ __('frontend.email_address') }}
                            <span class="text-primary ms-0.5" aria-hidden="true">*</span>
                        </label>
                        <input type="email" name="email" id="email" required
                               value="{{ old('email') }}"
                               placeholder="example@domain.com"
                               class="w-full bg-transparent border-0 border-b-2 border-secondary/60
                                      focus:border-primary focus:outline-none focus:ring-0
                                      py-2.5 text-sm text-text-primary
                                      placeholder:text-text-primary/35
                                      transition-colors duration-200">
                        @error('email')
                            <p class="text-xs text-red-500 mt-1" role="alert">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- ── Phone + Message Type row ────────────────────────── --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">

                    {{-- Phone --}}
                    <div class="space-y-2">
                        <label for="phone" class="block text-sm font-bold text-text-primary">
                            {{ __('frontend.phone_number') }}
                        </label>
                        <input type="text" name="phone" id="phone"
                               value="{{ old('phone') }}"
                               placeholder="0500000000"
                               class="w-full bg-transparent border-0 border-b-2 border-secondary/60
                                      focus:border-primary focus:outline-none focus:ring-0
                                      py-2.5 text-sm text-text-primary
                                      placeholder:text-text-primary/35
                                      transition-colors duration-200">
                        @error('phone')
                            <p class="text-xs text-red-500 mt-1" role="alert">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Message Type --}}
                    <div class="space-y-2">
                        <label for="type" class="block text-sm font-bold text-text-primary">
                            {{ __('frontend.message_type') }}
                        </label>
                        <select name="type" id="type"
                                class="w-full bg-transparent border-0 border-b-2 border-secondary/60
                                       focus:border-primary focus:outline-none focus:ring-0
                                       py-2.5 text-sm text-text-primary
                                       transition-colors duration-200 cursor-pointer">
                            <option value="general" {{ old('type') === 'general' ? 'selected' : '' }}>
                                {{ __('frontend.general_inquiry') }}
                            </option>
                            <option value="complaint" {{ old('type') === 'complaint' ? 'selected' : '' }}>
                                {{ __('frontend.complaint_suggestion') }}
                            </option>
                        </select>
                        @error('type')
                            <p class="text-xs text-red-500 mt-1" role="alert">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- ── Subject ─────────────────────────────────────────── --}}
                <div class="space-y-2">
                    <label for="subject" class="block text-sm font-bold text-text-primary">
                        {{ __('frontend.subject') }}
                    </label>
                    <input type="text" name="subject" id="subject"
                           value="{{ old('subject') }}"
                           placeholder="{{ __('frontend.enter_subject') }}"
                           class="w-full bg-transparent border-0 border-b-2 border-secondary/60
                                  focus:border-primary focus:outline-none focus:ring-0
                                  py-2.5 text-sm text-text-primary
                                  placeholder:text-text-primary/35
                                  transition-colors duration-200">
                    @error('subject')
                        <p class="text-xs text-red-500 mt-1" role="alert">{{ $message }}</p>
                    @enderror
                </div>

                {{-- ── Message ──────────────────────────────────────────── --}}
                <div class="space-y-2">
                    <label for="message" class="block text-sm font-bold text-text-primary">
                        {{ __('frontend.message') }}
                        <span class="text-primary ms-0.5" aria-hidden="true">*</span>
                    </label>
                    <textarea name="message" id="message" rows="5" required
                              placeholder="{{ __('frontend.write_message_here') }}"
                              class="w-full bg-transparent border-0 border-b-2 border-secondary/60
                                     focus:border-primary focus:outline-none focus:ring-0
                                     py-2.5 text-sm text-text-primary
                                     placeholder:text-text-primary/35
                                     resize-none transition-colors duration-200">{{ old('message') }}</textarea>
                    @error('message')
                        <p class="text-xs text-red-500 mt-1" role="alert">{{ $message }}</p>
                    @enderror
                </div>

                {{-- ── Submit ───────────────────────────────────────────── --}}
                <div class="pt-2">
                    <x-frontend.button type="submit" variant="primary" size="lg"
                                       class="w-full sm:w-auto px-10">
                        {{ __('frontend.send_message') }}
                    </x-frontend.button>
                </div>
            </form>
        </div>

        {{-- ── Map & Info Column (5/12) ──────────────────────────────────── --}}
        <div class="lg:col-span-5 flex flex-col"
             x-data="{ inView: false }"
             x-intersect.once="inView = true">
            <div class="flex flex-col h-full gap-6"
                 :class="inView ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-6'"
                 style="transition: opacity 0.7s 0.15s ease-out, transform 0.7s 0.15s ease-out;">

                {{-- Location heading --}}
                <div>
                    <h3 class="text-lg font-extrabold text-text-primary flex items-center gap-3">
                        <span class="flex items-center justify-center w-8 h-8 rounded-full bg-primary/10 text-primary shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/>
                            </svg>
                        </span>
                        {{ __('frontend.our_location') }}
                    </h3>
                    @if ($locationName)
                        <p class="text-sm text-text-primary/55 mt-1 ms-11 font-medium">
                            {{ $locationName }}
                        </p>
                    @endif
                </div>

                {{-- Google Map --}}
                <div class="relative w-full rounded-2xl overflow-hidden border border-secondary/30 shadow-sm bg-secondary/20"
                     style="aspect-ratio: 4/3; min-height: 240px;">
                    <iframe
                        src="{{ $mapIframeUrl }}"
                        class="absolute inset-0 w-full h-full border-0"
                        allowfullscreen=""
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade"
                        title="{{ __('frontend.our_location') }}"
                    ></iframe>
                </div>

                {{-- Contact detail rows ───────────────────────────────────── --}}
                <div class="space-y-4 pt-2 border-t border-secondary/25">

                    {{-- Address --}}
                    <div class="flex items-start gap-3">
                        <span class="flex items-center justify-center w-8 h-8 rounded-full bg-secondary/70 text-primary shrink-0 mt-0.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008z"/>
                            </svg>
                        </span>
                        <div>
                            <p class="text-[10px] font-extrabold text-text-primary/40 uppercase tracking-widest">
                                {{ __('frontend.address') }}
                            </p>
                            <p class="text-sm font-medium text-text-primary mt-0.5 leading-relaxed">
                                {{ $siteAddress }}
                            </p>
                        </div>
                    </div>

                    {{-- Phone --}}
                    <div class="flex items-start gap-3">
                        <span class="flex items-center justify-center w-8 h-8 rounded-full bg-secondary/70 text-primary shrink-0 mt-0.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z"/>
                            </svg>
                        </span>
                        <div>
                            <p class="text-[10px] font-extrabold text-text-primary/40 uppercase tracking-widest">
                                {{ __('frontend.phone_whatsapp') }}
                            </p>
                            <a href="tel:{{ str_replace(' ', '', $sitePhone) }}"
                               class="text-sm font-semibold text-primary hover:text-primary/80 hover:underline mt-0.5 inline-block transition-colors"
                               dir="ltr">
                                {{ $sitePhone }}
                            </a>
                        </div>
                    </div>

                    {{-- Email --}}
                    <div class="flex items-start gap-3">
                        <span class="flex items-center justify-center w-8 h-8 rounded-full bg-secondary/70 text-primary shrink-0 mt-0.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/>
                            </svg>
                        </span>
                        <div>
                            <p class="text-[10px] font-extrabold text-text-primary/40 uppercase tracking-widest">
                                {{ __('frontend.email') }}
                            </p>
                            <a href="mailto:{{ $siteEmail }}"
                               class="text-sm font-semibold text-primary hover:text-primary/80 hover:underline mt-0.5 inline-block transition-colors">
                                {{ $siteEmail }}
                            </a>
                        </div>
                    </div>
                </div>

                {{-- Google Maps CTA --}}
                @if ($googleMapsUrl)
                    <div>
                        <a href="{{ $googleMapsUrl }}" target="_blank" rel="noopener noreferrer"
                           class="w-full inline-flex items-center justify-center gap-2.5
                                  rounded-2xl bg-primary text-background
                                  px-5 py-3 text-sm font-bold
                                  hover:bg-primary/90 shadow-sm hover:shadow-md
                                  transition-all duration-200 active:scale-[0.98]"
                           aria-label="{{ __('frontend.open_in_google_maps') }}">
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor"
                                 viewBox="0 0 24 24" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M9 6.75V15m6-6v8.25m.503 3.498l4.875-2.437c.381-.19.622-.58.622-1.006V4.82c0-.836-.88-1.38-1.628-1.006l-3.869 1.934c-.317.159-.69.159-1.006 0L9.503 3.252a1.125 1.125 0 00-1.006 0L3.622 5.689C3.24 5.88 3 6.27 3 6.695V19.18c0 .836.88 1.38 1.628 1.006l3.869-1.934c.317-.159.69-.159 1.006 0l4.994 2.497c.317.158.69.158 1.006 0z"/>
                            </svg>
                            <span>{{ __('frontend.open_in_google_maps') }}</span>
                        </a>
                    </div>
                @endif

            </div>
        </div>

    </div>

</x-frontend-layout>
