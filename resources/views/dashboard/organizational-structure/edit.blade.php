@extends('layouts.app')

@section('title', __('dashboard.sidebar.organizational_structure'))

@php
    $breadcrumbs = [['label' => __('dashboard.sidebar.organizational_structure'), 'url' => null]];
    $imgArUrl = $structure ? \App\Helpers\MediaHelper::url($structure, 'organizational_structure_ar', 'image_ar') : null;
    $imgEnUrl = $structure ? \App\Helpers\MediaHelper::url($structure, 'organizational_structure_en', 'image_en') : null;
@endphp

@section('content')

    <h1 class="mb-6 text-xl font-bold text-[#3D342A]">{{ __('dashboard.sidebar.organizational_structure') }}</h1>

    <x-alerts.success />
    <x-alerts.error />

    <form method="POST" action="{{ route('dashboard.organizational-structure.update') }}" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        {{-- Basic Information --}}
        <div class="rounded-xl border border-[#B49C6E]/20 bg-secondary p-5">
            <h2 class="mb-4 text-base font-semibold text-[#3D342A]">{{ __('dashboard.common.details') }}</h2>
            
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                {{-- Title Arabic --}}
                <div>
                    <label for="title_ar" class="mb-1.5 block text-sm font-semibold text-[#3D342A]">
                        {{ __('dashboard.common.title') }} (العربية) <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="text"
                        name="title_ar"
                        id="title_ar"
                        value="{{ old('title_ar', $structure->title_ar ?? '') }}"
                        required
                        class="w-full rounded-lg border border-[#B49C6E]/40 bg-background px-3.5 py-2 text-sm text-[#3D342A] shadow-sm focus:border-primary focus:outline-none"
                    />
                    @error('title_ar')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Title English --}}
                <div>
                    <label for="title_en" class="mb-1.5 block text-sm font-semibold text-[#3D342A]">
                        {{ __('dashboard.common.title') }} (English)
                    </label>
                    <input
                        type="text"
                        name="title_en"
                        id="title_en"
                        value="{{ old('title_en', $structure->title_en ?? '') }}"
                        class="w-full rounded-lg border border-[#B49C6E]/40 bg-background px-3.5 py-2 text-sm text-[#3D342A] shadow-sm focus:border-primary focus:outline-none"
                    />
                    @error('title_en')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Description Arabic --}}
                <div>
                    <label for="description_ar" class="mb-1.5 block text-sm font-semibold text-[#3D342A]">
                        {{ __('dashboard.common.description') }} (العربية)
                    </label>
                    <textarea
                        name="description_ar"
                        id="description_ar"
                        rows="3"
                        class="w-full rounded-lg border border-[#B49C6E]/40 bg-background px-3.5 py-2 text-sm text-[#3D342A] shadow-sm focus:border-primary focus:outline-none"
                    >{{ old('description_ar', $structure->description_ar ?? '') }}</textarea>
                    @error('description_ar')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Description English --}}
                <div>
                    <label for="description_en" class="mb-1.5 block text-sm font-semibold text-[#3D342A]">
                        {{ __('dashboard.common.description') }} (English)
                    </label>
                    <textarea
                        name="description_en"
                        id="description_en"
                        rows="3"
                        class="w-full rounded-lg border border-[#B49C6E]/40 bg-background px-3.5 py-2 text-sm text-[#3D342A] shadow-sm focus:border-primary focus:outline-none"
                    >{{ old('description_en', $structure->description_en ?? '') }}</textarea>
                    @error('description_en')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Active Status Toggle --}}
            <div class="mt-5 flex items-center gap-2">
                <input
                    type="checkbox"
                    name="is_active"
                    id="is_active"
                    value="1"
                    {{ old('is_active', $structure->is_active ?? true) ? 'checked' : '' }}
                    class="h-4.5 w-4.5 rounded border-[#B49C6E]/40 text-primary focus:ring-primary"
                />
                <label for="is_active" class="text-sm font-semibold text-[#3D342A]">
                    {{ __('dashboard.common.active') }}
                </label>
            </div>
        </div>

        {{-- Bilingual Chart Images Upload --}}
        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
            
            {{-- Image Arabic --}}
            <div class="rounded-xl border border-[#B49C6E]/20 bg-secondary p-5">
                <h2 class="mb-4 text-base font-semibold text-[#3D342A]">مخطط الهيكل (العربية)</h2>
                
                @if ($imgArUrl)
                    <div class="mb-4">
                        <p class="mb-1.5 text-xs font-semibold text-[#3D342A]/70">المخطط الحالي:</p>
                        <div class="relative inline-block overflow-hidden rounded-lg border border-[#B49C6E]/40 bg-background p-2">
                            <img src="{{ $imgArUrl }}" alt="Arabic chart" class="max-h-48 max-w-full object-contain" />
                        </div>
                        <div class="mt-2.5 flex items-center gap-2">
                            <input type="checkbox" name="remove_image_ar" id="remove_image_ar" value="1" class="h-4 w-4 rounded border-[#B49C6E]/40 text-primary" />
                            <label for="remove_image_ar" class="text-xs font-semibold text-red-700">إزالة المخطط الحالي</label>
                        </div>
                    </div>
                @endif

                <div class="mt-2">
                    <label for="image_ar" class="mb-1 block text-xs font-semibold text-[#3D342A]">
                        رفع مخطط جديد (Arabic Chart)
                    </label>
                    <input
                        type="file"
                        name="image_ar"
                        id="image_ar"
                        accept="image/jpeg,image/png,image/jpg,image/webp,image/svg+xml"
                        class="block w-full text-xs text-[#3D342A] file:me-3 file:rounded-md file:border-0 file:bg-primary file:px-3 file:py-1.5 file:text-xs file:font-semibold file:text-background hover:file:bg-primary"
                    />
                    <p class="mt-1 text-[10px] text-[#3D342A]/60">التنسيقات المدعومة: JPEG, PNG, JPG, WEBP, SVG. الحد الأقصى: 5 ميغابايت.</p>
                    @error('image_ar')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Image English --}}
            <div class="rounded-xl border border-[#B49C6E]/20 bg-secondary p-5">
                <h2 class="mb-4 text-base font-semibold text-[#3D342A]">مخطط الهيكل (English)</h2>
                
                @if ($imgEnUrl)
                    <div class="mb-4">
                        <p class="mb-1.5 text-xs font-semibold text-[#3D342A]/70">Current Chart:</p>
                        <div class="relative inline-block overflow-hidden rounded-lg border border-[#B49C6E]/40 bg-background p-2">
                            <img src="{{ $imgEnUrl }}" alt="English chart" class="max-h-48 max-w-full object-contain" />
                        </div>
                        <div class="mt-2.5 flex items-center gap-2">
                            <input type="checkbox" name="remove_image_en" id="remove_image_en" value="1" class="h-4 w-4 rounded border-[#B49C6E]/40 text-primary" />
                            <label for="remove_image_en" class="text-xs font-semibold text-red-700">Remove current chart</label>
                        </div>
                    </div>
                @endif

                <div class="mt-2">
                    <label for="image_en" class="mb-1 block text-xs font-semibold text-[#3D342A]">
                        Upload New English Chart
                    </label>
                    <input
                        type="file"
                        name="image_en"
                        id="image_en"
                        accept="image/jpeg,image/png,image/jpg,image/webp,image/svg+xml"
                        class="block w-full text-xs text-[#3D342A] file:me-3 file:rounded-md file:border-0 file:bg-primary file:px-3 file:py-1.5 file:text-xs file:font-semibold file:text-background hover:file:bg-primary"
                    />
                    <p class="mt-1 text-[10px] text-[#3D342A]/60">Supported: JPEG, PNG, JPG, WEBP, SVG. Max: 5MB.</p>
                    @error('image_en')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

        </div>

        {{-- Form Actions --}}
        <div class="flex items-center justify-end gap-3 border-t border-[#B49C6E]/20 pt-4">
            <button
                type="submit"
                class="rounded-lg bg-primary px-5 py-2.5 text-sm font-semibold text-background shadow hover:bg-primary active:scale-[0.98] transition-all"
            >
                {{ __('dashboard.common.save_changes') }}
            </button>
        </div>
    </form>

@endsection
