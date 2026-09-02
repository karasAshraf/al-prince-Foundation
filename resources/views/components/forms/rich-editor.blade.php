@props([
    'name',
    'label' => null,
    'value' => null,
    'required' => false,
    'hint' => null,
])

<div>
    @if($label)
        <label for="{{ $name }}" class="mb-1.5 block text-sm font-medium text-[#3D342A]">
            {{ $label }}
            @if($required)
                <span class="text-red-500">*</span>
            @endif
        </label>
    @endif

    <div class="rounded-lg border border-[#B49C6E]/40 bg-secondary overflow-hidden focus-within:border-[#A38B54] focus-within:ring-1 focus-within:ring-[#A38B54]">
        <textarea
            name="{{ $name }}"
            id="{{ $name }}"
            rows="6"
            {{ $required ? 'required' : '' }}
            {{ $attributes->merge([
                'class' => 'w-full border-0 bg-transparent px-3.5 py-2.5 text-sm text-[#3D342A] placeholder-[#3D342A]/40 focus:outline-none focus:ring-0'
            ]) }}
        >{{ old($name, $value) }}</textarea>
    </div>

    @if($hint)
        <p class="mt-1 text-xs text-[#3D342A]/60">{{ $hint }}</p>
    @endif

    @error($name)
        <p class="mt-1 text-xs font-medium text-red-600">{{ $message }}</p>
    @enderror
</div>
