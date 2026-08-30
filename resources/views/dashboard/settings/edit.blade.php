@extends('layouts.app')

@section('title', __('dashboard.settings.title'))

@php
    $breadcrumbs = [['label' => __('dashboard.settings.title'), 'url' => null]];

    // Initialize Phone Numbers list
    $rawPhones = old('phone_numbers');
    if (is_null($rawPhones)) {
        $rawPhones = $companyInfo['phone_numbers'] ?? [];
    }
    if (is_string($rawPhones)) {
        $rawPhones = [$rawPhones];
    }
    $phoneNumbersList = array_values(array_filter((array) $rawPhones, fn($p) => is_string($p)));
    if (empty($phoneNumbersList)) {
        $phoneNumbersList = [''];
    }

    // Initialize Social Links list
    $rawSocial = old('social_links');
    $socialLinksList = [];

    if (is_array($rawSocial) && !empty($rawSocial)) {
        foreach ($rawSocial as $k => $v) {
            if (is_array($v) && isset($v['url'])) {
                $socialLinksList[] = ['platform' => $v['platform'] ?? 'facebook', 'url' => $v['url']];
            }
        }
    }

    if (empty($socialLinksList) && !empty($socialLinks) && is_array($socialLinks)) {
        foreach ($socialLinks as $platform => $url) {
            if (is_string($url) && filled($url)) {
                $socialLinksList[] = ['platform' => $platform, 'url' => $url];
            } elseif (is_array($url) && isset($url['url'])) {
                $socialLinksList[] = ['platform' => $url['platform'] ?? $platform, 'url' => $url['url']];
            }
        }
    }

    if (empty($socialLinksList)) {
        $socialLinksList = [
            ['platform' => 'facebook', 'url' => ''],
            ['platform' => 'twitter', 'url' => ''],
            ['platform' => 'instagram', 'url' => ''],
        ];
    }
@endphp

@section('content')

    <h1 class="mb-6 text-xl font-bold text-[#3D342A]">{{ __('dashboard.settings.title') }}</h1>

    <x-alerts.success />
    <x-alerts.error />

    <form method="POST" action="{{ route('dashboard.settings.update') }}" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        {{-- General Info --}}
        <div class="rounded-xl border border-[#B49C6E]/20 bg-[#EAEAE9] p-5">
            <h2 class="mb-4 text-base font-semibold text-[#3D342A]">{{ __('dashboard.settings.general_settings') }}</h2>

            {{-- Current Logo Preview & Upload --}}
            <div class="mb-5 flex flex-col gap-3 rounded-lg border border-[#B49C6E]/30 bg-[#EAEAE9]/20 p-4 sm:flex-row sm:items-center sm:justify-between">
                <div class="flex items-center gap-4">
                    <div class="flex h-14 w-28 items-center justify-center rounded-lg border border-[#B49C6E]/40 bg-white p-2 shadow-sm">
                        <x-application-logo class="max-h-full max-w-full object-contain" />
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-[#3D342A]">شعار المؤسسة (Logo)</p>
                        <p class="text-xs text-[#3D342A]/60">يُعرض الشعار تلقائياً في الشريط العلوي وصفحة الدخول</p>
                    </div>
                </div>
                <div class="w-full sm:w-auto flex flex-col gap-2">
                    <div>
                        <label class="text-xs font-semibold text-[#3D342A] block mb-1">رفع صورة الشعار</label>
                        <input
                            type="file"
                            name="logo"
                            id="logo"
                            accept="image/*"
                            class="block w-full text-xs text-[#3D342A] file:me-3 file:rounded-md file:border-0 file:bg-[#A38B54] file:px-3 file:py-1.5 file:text-xs file:font-semibold file:text-white hover:file:bg-[#3D342A]"
                        />
                        @error('logo')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="relative flex py-1 items-center">
                        <div class="flex-grow border-t border-[#B49C6E]/30"></div>
                        <span class="flex-shrink mx-2 text-[#3D342A]/50 text-xs">أو (Or)</span>
                        <div class="flex-grow border-t border-[#B49C6E]/30"></div>
                    </div>

                    <div>
                        <label class="text-xs font-semibold text-[#3D342A] block mb-1">رابط الشعار الخارجي (External Image URL)</label>
                        @php
                            $dbLogo = $companyInfo['logo'] ?? '';
                            $logoExternalVal = (is_string($dbLogo) && preg_match('/^(https?:)?\/\//i', $dbLogo)) ? $dbLogo : '';
                        @endphp
                        <input
                            type="text"
                            name="logo_external"
                            id="logo_external"
                            placeholder="https://example.com/logo.png"
                            value="{{ old('logo_external', $logoExternalVal) }}"
                            class="w-full sm:w-72 rounded-lg border border-[#B49C6E]/40 bg-[#EAEAE9] px-3 py-1.5 text-xs text-[#3D342A] focus:border-[#A38B54] focus:outline-none placeholder:text-gray-400"
                        />
                        @error('logo_external')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <x-forms.input
                    name="name_ar"
                    label="{{ __('dashboard.settings.site_name') }} (AR)"
                    :value="old('name_ar', $companyInfo['name_ar'] ?? '')"
                    required
                />
                <x-forms.input
                    name="name_en"
                    label="{{ __('dashboard.settings.site_name') }} (EN)"
                    :value="old('name_en', $companyInfo['name_en'] ?? '')"
                />
                <x-forms.input
                    name="email"
                    label="{{ __('dashboard.settings.contact_email') }}"
                    type="email"
                    :value="old('email', $companyInfo['email'] ?? '')"
                    required
                />

                {{-- Dynamic Phone Numbers Component --}}
                <div
                    x-data="{
                        phones: @js($phoneNumbersList),
                        addPhone() {
                            this.phones.push('');
                        },
                        removePhone(index) {
                            if (this.phones.length > 1) {
                                this.phones.splice(index, 1);
                            } else {
                                this.phones = [''];
                            }
                        }
                    }"
                    class="space-y-3 sm:col-span-2"
                >
                    <div class="flex items-center justify-between">
                        <label class="block text-sm font-medium text-[#3D342A]">
                            {{ __('dashboard.settings.contact_phone') }}
                        </label>
                        <button
                            type="button"
                            @click="addPhone()"
                            class="inline-flex items-center gap-1 text-xs font-semibold text-[#A38B54] hover:text-[#3D342A] transition"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            <span>إضافة رقم هاتف آخر</span>
                        </button>
                    </div>

                    <div class="space-y-2">
                        <template x-for="(phone, index) in phones" :key="index">
                            <div class="flex items-center gap-2">
                                <input
                                    type="text"
                                    name="phone_numbers[]"
                                    x-model="phones[index]"
                                    placeholder="012XXXXXXXX"
                                    dir="ltr"
                                    class="block w-full rounded-lg border border-[#B49C6E]/40 bg-[#EAEAE9] px-3.5 py-2 text-sm text-[#3D342A] focus:border-[#A38B54] focus:outline-none focus:ring-1 focus:ring-[#A38B54]"
                                >
                                <button
                                    type="button"
                                    @click="removePhone(index)"
                                    class="inline-flex h-9 w-9 shrink-0 items-center justify-center rounded-lg border border-red-200 bg-red-50 text-red-600 transition hover:bg-red-100 hover:text-red-700"
                                    title="حذف الرقم"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </div>
                        </template>
                    </div>
                    @error('phone_numbers')
                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                    @error('phone_numbers.*')
                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <x-forms.textarea
                    name="address_ar"
                    label="{{ __('dashboard.settings.address') }} (AR)"
                    :value="old('address_ar', $companyInfo['address_ar'] ?? '')"
                    rows="2"
                />
                <x-forms.textarea
                    name="address_en"
                    label="{{ __('dashboard.settings.address') }} (EN)"
                    :value="old('address_en', $companyInfo['address_en'] ?? '')"
                    rows="2"
                />
                <x-forms.textarea
                    name="description_ar"
                    label="{{ __('dashboard.settings.site_description') }} (AR)"
                    :value="old('description_ar', $companyInfo['description_ar'] ?? $companyInfo['description'] ?? '')"
                    rows="3"
                />
                <x-forms.textarea
                    name="description_en"
                    label="{{ __('dashboard.settings.site_description') }} (EN)"
                    :value="old('description_en', $companyInfo['description_en'] ?? '')"
                    rows="3"
                />
                <x-forms.input
                    name="copyright_ar"
                    label="نص حقوق الملكية (AR)"
                    :value="old('copyright_ar', $companyInfo['copyright_ar'] ?? $companyInfo['copyright'] ?? '')"
                />
                <x-forms.input
                    name="copyright_en"
                    label="نص حقوق الملكية (EN)"
                    :value="old('copyright_en', $companyInfo['copyright_en'] ?? '')"
                />
                <x-forms.input
                    name="location_name_ar"
                    label="{{ __('dashboard.settings.location_name_ar') }}"
                    :value="old('location_name_ar', $companyInfo['location_name_ar'] ?? '')"
                />
                <x-forms.input
                    name="location_name_en"
                    label="{{ __('dashboard.settings.location_name_en') }}"
                    :value="old('location_name_en', $companyInfo['location_name_en'] ?? '')"
                />
                <div class="sm:col-span-2">
                    <x-forms.input
                        name="google_maps_url"
                        label="{{ __('dashboard.settings.google_maps_url') }}"
                        type="url"
                        :value="old('google_maps_url', $companyInfo['google_maps_url'] ?? '')"
                        placeholder="https://maps.google.com/..."
                    />
                </div>
            </div>
        </div>

        {{-- Dynamic Social Media Links Component --}}
        <div
            x-data="{
                links: @js($socialLinksList),
                addLink() {
                    this.links.push({ platform: 'facebook', url: '' });
                },
                removeLink(index) {
                    if (this.links.length > 1) {
                        this.links.splice(index, 1);
                    } else {
                        this.links = [{ platform: 'facebook', url: '' }];
                    }
                }
            }"
            class="rounded-xl border border-[#B49C6E]/20 bg-[#EAEAE9] p-5 space-y-4"
        >
            <div class="flex items-center justify-between border-b border-[#B49C6E]/20 pb-3">
                <h2 class="text-base font-semibold text-[#3D342A]">{{ __('dashboard.settings.social_links') }}</h2>
                <button
                    type="button"
                    @click="addLink()"
                    class="inline-flex items-center gap-1 text-xs font-semibold text-[#A38B54] hover:text-[#3D342A] transition"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    <span>إضافة رابط حساب جديد</span>
                </button>
            </div>

            <div class="space-y-3">
                <template x-for="(link, index) in links" :key="index">
                    <div class="flex flex-col gap-2 sm:flex-row sm:items-center">
                        <select
                            :name="'social_links[' + index + '][platform]'"
                            x-model="link.platform"
                            class="w-full sm:w-44 rounded-lg border border-[#B49C6E]/40 bg-[#EAEAE9] px-3 py-2 text-sm text-[#3D342A] focus:border-[#A38B54] focus:outline-none focus:ring-1 focus:ring-[#A38B54]"
                        >
                            <option value="facebook">فيسبوك (Facebook)</option>
                            <option value="twitter">إكس (Twitter/X)</option>
                            <option value="instagram">انستجرام (Instagram)</option>
                            <option value="linkedin">لينكد إن (LinkedIn)</option>
                            <option value="youtube">يوتيوب (YouTube)</option>
                            <option value="tiktok">تيك توك (TikTok)</option>
                            <option value="whatsapp">واتساب (WhatsApp)</option>
                            <option value="telegram">تلجرام (Telegram)</option>
                            <option value="website">موقع إلكتروني (Website)</option>
                        </select>

                        <input
                            type="url"
                            :name="'social_links[' + index + '][url]'"
                            x-model="link.url"
                            placeholder="https://example.com/username"
                            dir="ltr"
                            class="block w-full rounded-lg border border-[#B49C6E]/40 bg-[#EAEAE9] px-3.5 py-2 text-sm text-[#3D342A] focus:border-[#A38B54] focus:outline-none focus:ring-1 focus:ring-[#A38B54]"
                        >

                        <button
                            type="button"
                            @click="removeLink(index)"
                            class="inline-flex h-9 w-9 shrink-0 items-center justify-center rounded-lg border border-red-200 bg-red-50 text-red-600 transition hover:bg-red-100 hover:text-red-700 self-end sm:self-auto"
                            title="حذف الرابط"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>
                    </div>
                </template>
            </div>
            @error('social_links')
                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
            @enderror
            @error('social_links.*')
                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex items-center gap-3">
            <x-buttons.primary type="submit">{{ __('dashboard.common.save') }}</x-buttons.primary>
        </div>
    </form>

@endsection
