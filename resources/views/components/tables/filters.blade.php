@props([
    'name',
    'label' => null,
    'options' => [],
])

<form
    method="GET"
    x-data
    @change="$el.submit()"
>
    @foreach(request()->except($name, 'page') as $key => $value)
        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
    @endforeach

    <select
        name="{{ $name }}"
        class="rounded-lg border border-[#B49C6E]/40 bg-[#EAEAE9] px-3 py-2 text-sm text-[#3D342A] focus:border-[#A38B54] focus:outline-none focus:ring-1 focus:ring-[#A38B54]"
    >
        <option value="">{{ $label ?? 'الكل' }}</option>
        @foreach($options as $value => $optionLabel)
            <option value="{{ $value }}" {{ request($name) == $value ? 'selected' : '' }}>
                {{ $optionLabel }}
            </option>
        @endforeach
    </select>
</form>