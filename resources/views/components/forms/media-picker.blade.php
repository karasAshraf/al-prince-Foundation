@props([
    'name' => 'media',
    'label' => null,
    'selectedUrl' => null,
])

<div class="space-y-2">
    @if($label)
        <label class="block text-sm font-medium text-[#3D342A]">{{ $label }}</label>
    @endif

    <x-forms.input
        name="{{ $name }}"
        :value="$selectedUrl"
        placeholder="رابط الملف أو الصورة..."
    />
</div>
