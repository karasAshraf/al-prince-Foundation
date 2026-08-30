@props([
    'name' => 'slug',
    'label' => 'الرابط (Slug)',
    'value' => null,
    'sourceField' => 'title_ar',
    'required' => false,
])

<div
    x-data="{
        slug: @js(old($name, $value)),
        locked: {{ old($name, $value) ? 'true' : 'false' }},
        makeSlug(text) {
            return text
                .trim()
                .replace(/[\u064B-\u0652]/g, '')
                .replace(/[^a-zA-Z0-9\u0600-\u06FF\s-]/g, '')
                .replace(/\s+/g, '-')
                .toLowerCase();
        }
    }"
    @title-changed.window="if (!locked) slug = makeSlug($event.detail)"
>
    <div class="mb-1.5 flex items-center justify-between">
        <label for="{{ $name }}" class="block text-sm font-medium text-[#3D342A]">
            {{ $label }}
            @if($required)
                <span class="text-red-500">*</span>
            @endif
        </label>

        {{-- Lets the admin manually override the auto-generated slug --}}
        <button
            type="button"
            @click="locked = !locked"
            class="text-xs text-[#A38B54] hover:underline"
            x-text="locked ? 'تعديل يدوي' : 'إيقاف التعديل اليدوي'"
        ></button>
    </div>

    <input
        type="text"
        name="{{ $name }}"
        id="{{ $name }}"
        x-model="slug"
        :readonly="locked && !{{ old($name, $value) ? 'false' : 'true' }}"
        {{ $required ? 'required' : '' }}
        {{ $attributes->merge([
            'class' => 'w-full rounded-lg border border-[#B49C6E]/40 bg-[#EAEAE9] px-3 py-2 text-sm text-[#3D342A] focus:border-[#A38B54] focus:outline-none focus:ring-1 focus:ring-[#A38B54] disabled:bg-[#EAEAE9]/30'
        ]) }}
    >

    @error($name)
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>