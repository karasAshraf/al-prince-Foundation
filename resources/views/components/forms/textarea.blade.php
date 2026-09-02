@props([
    'name',
    'label' => null,
    'value' => null,
    'rows' => 3,
    'required' => false,
    'placeholder' => null,
    'hint' => null,
])

@php
    $hasError = $errors->has($name);
    $borderClasses = $hasError
        ? 'border-2 border-red-500 focus:border-red-600 focus:ring-2 focus:ring-red-500/20'
        : 'border border-[#B49C6E]/40 hover:border-[#B49C6E] focus:border-[#A38B54] focus:ring-2 focus:ring-[#A38B54]/20';
@endphp

<div>
    @if($label)
        <label for="{{ $name }}" class="mb-1.5 block text-sm font-medium text-[#3D342A]">
            {{ $label }}
            @if($required)
                <span class="text-red-500">*</span>
            @endif
        </label>
    @endif

    <textarea
        name="{{ $name }}"
        id="{{ $name }}"
        rows="{{ $rows }}"
        {{ $required ? 'required' : '' }}
        @if($placeholder) placeholder="{{ $placeholder }}" @endif
        {{ $attributes->merge([
            'class' => "w-full rounded-xl bg-secondary px-3.5 py-2.5 text-sm text-[#3D342A] placeholder-[#3D342A]/40 leading-relaxed transition duration-200 focus:outline-none disabled:opacity-50 disabled:bg-secondary/20 disabled:cursor-not-allowed {$borderClasses}"
        ]) }}
    >{{ old($name, $value) }}</textarea>

    @if($hint)
        <p class="mt-1 text-xs text-[#3D342A]/60">{{ $hint }}</p>
    @endif

    @error($name)
        <p class="mt-1.5 text-xs font-medium text-red-600 flex items-center gap-1">
            <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span>{{ $message }}</span>
        </p>
    @enderror
</div>
