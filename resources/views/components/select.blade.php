@props([
    'name',
    'label' => null,
    'options' => [],
    'selected' => null,
    'required' => false,
    'placeholder' => 'اختر...',
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

    <select
        name="{{ $name }}"
        id="{{ $name }}"
        {{ $required ? 'required' : '' }}
        {{ $attributes->merge([
            'class' => 'w-full rounded-lg border border-[#B49C6E]/40 bg-[#EAEAE9] px-3 py-2 text-sm text-[#3D342A] focus:border-[#A38B54] focus:outline-none focus:ring-1 focus:ring-[#A38B54]'
        ]) }}
    >
        @if($placeholder)
            <option value="" disabled {{ old($name, $selected) ? '' : 'selected' }}>
                {{ $placeholder }}
            </option>
        @endif

        @foreach($options as $value => $optionLabel)
            <option
                value="{{ $value }}"
                {{ (string) old($name, $selected) === (string) $value ? 'selected' : '' }}
            >
                {{ $optionLabel }}
            </option>
        @endforeach
    </select>

    @error($name)
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>