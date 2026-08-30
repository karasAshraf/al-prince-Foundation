@props([
    'value' => null,
    'name'  => null,
    'label' => null,
])

@php
    $text = $value ?? $label ?? null;
@endphp

<label {{ $attributes->merge(['class' => 'block text-sm font-medium text-[#3D342A]']) }}>
    {{ $text ?? $slot }}
</label>