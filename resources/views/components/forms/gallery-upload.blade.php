@props([
    'name',
    'label'      => null,
    'existing'   => [],
    'removeName' => 'remove_gallery',
    'max'        => 10,
    'required'   => false,
])

<div x-data="{
    previews: [],
    handleFiles(event) {
        const files = Array.from(event.target.files);
        files.forEach(file => {
            const reader = new FileReader();
            reader.onload = (e) => {
                if (this.previews.length < {{ $max }}) {
                    this.previews.push({ name: file.name, src: e.target.result });
                }
            };
            reader.readAsDataURL(file);
        });
    },
    remove(index) {
        this.previews.splice(index, 1);
    }
}" class="space-y-3">
    @if($label)
        <label class="mb-1.5 block text-sm font-medium text-[#3D342A]">
            {{ $label }}
            @if($required) <span class="text-red-500">*</span> @endif
        </label>
    @endif

    {{-- Existing images with remove functionality --}}
    @if(count($existing))
        <div class="space-y-1.5">
            <span class="block text-xs font-semibold text-[#3D342A]">الصور الحالية:</span>
            <div class="grid grid-cols-3 gap-2 sm:grid-cols-4 lg:grid-cols-6">
                @foreach($existing as $img)
                    @php
                        $imgId  = is_array($img) ? ($img['id'] ?? null) : null;
                        $imgUrl = is_array($img) ? ($img['url'] ?? '') : $img;
                    @endphp
                    <div x-data="{ removed: false }" x-show="!removed" class="relative group">
                        <img src="{{ $imgUrl }}" alt="" class="h-20 w-full rounded-lg object-cover border border-[#B49C6E]/30 shadow-sm">
                        @if($imgId)
                            <button
                                type="button"
                                @click="removed = true"
                                class="absolute -end-1.5 -top-1.5 rounded-full bg-red-500 p-0.5 text-white hover:bg-red-600 shadow-sm transition"
                                title="إزالة الصورة"
                            >
                                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                            <template x-if="removed">
                                <input type="hidden" name="{{ $removeName }}[]" value="{{ $imgId }}">
                            </template>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    {{-- New upload previews --}}
    <template x-if="previews.length > 0">
        <div class="space-y-1.5">
            <span class="block text-xs font-semibold text-[#A38B54]">الصور الجاري إضافتها:</span>
            <div class="grid grid-cols-3 gap-2 sm:grid-cols-4 lg:grid-cols-6">
                <template x-for="(item, index) in previews" :key="index">
                    <div class="relative">
                        <img :src="item.src" :alt="item.name" class="h-20 w-full rounded-lg object-cover border border-[#A38B54]/30 shadow-sm">
                        <button
                            type="button"
                            @click="remove(index)"
                            class="absolute -end-1.5 -top-1.5 rounded-full bg-red-500 p-0.5 text-white hover:bg-red-600 shadow-sm transition"
                        >
                            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                </template>
            </div>
        </div>
    </template>

    {{-- Upload drop zone --}}
    <label class="flex cursor-pointer flex-col items-center justify-center rounded-xl border-2 border-dashed border-[#B49C6E]/40 bg-[#EAEAE9] px-4 py-5 text-center transition hover:border-[#A38B54] hover:bg-[#EAEAE9]/20">
        <svg xmlns="http://www.w3.org/2000/svg" class="mb-1 h-7 w-7 text-[#B49C6E]" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
        </svg>
        <p class="text-sm text-[#3D342A]/70">
            <span class="font-semibold text-[#A38B54]">اختر صور متعددة</span> أو اسحبها هنا
        </p>
        <p class="mt-1 text-xs text-[#3D342A]/40">حتى {{ $max }} صور</p>
        <input
            type="file"
            name="{{ $name }}[]"
            multiple
            accept="image/*"
            class="sr-only"
            @change="handleFiles($event)"
            {{ $required ? 'required' : '' }}
        >
    </label>

    @error($name)
        <p class="mt-1 text-xs font-medium text-red-600">{{ $message }}</p>
    @enderror
</div>
