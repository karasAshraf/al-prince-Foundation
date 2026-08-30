@props([
    'name',
    'label' => null,
    'checked' => false,
    'value' => '1',
])

<div>
    <label class="flex items-center gap-2 text-sm text-[#3D342A]">
        <input
            type="checkbox"
            name="{{ $name }}"
            id="{{ $name }}"
            value="{{ $value }}"
            {{ old($name, $checked) ? 'checked' : '' }}
            {{ $attributes->merge([
                'class' => 'h-4 w-4 rounded border-[#B49C6E]/40 text-[#A38B54] focus:ring-[#A38B54]'
            ]) }}
        >
        {{ $label }}
    </label>

    @error($name)
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>