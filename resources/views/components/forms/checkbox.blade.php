@props([
    'name',
    'label',
    'checked' => false,
    'value' => '1',
    'hint' => null,
])

<div class="flex items-start gap-3">
    <div class="flex h-5 items-center">
        <input
            type="checkbox"
            name="{{ $name }}"
            id="{{ $name }}"
            value="{{ $value }}"
            {{ old($name, $checked) ? 'checked' : '' }}
            {{ $attributes->merge([
                'class' => 'h-4.5 w-4.5 rounded border-[#B49C6E]/60 text-[#A38B54] transition duration-150 focus:ring-2 focus:ring-[#A38B54]/30 focus:ring-offset-1 focus-visible:ring-2 focus-visible:ring-[#A38B54] cursor-pointer disabled:opacity-50 disabled:cursor-not-allowed'
            ]) }}
        >
    </div>
    <div class="text-sm">
        <label for="{{ $name }}" class="font-medium text-[#3D342A] cursor-pointer select-none">
            {{ $label }}
        </label>
        @if($hint)
            <p class="text-xs text-[#3D342A]/60">{{ $hint }}</p>
        @endif
        @error($name)
            <p class="mt-1 text-xs font-medium text-red-600">{{ $message }}</p>
        @enderror
    </div>
</div>
