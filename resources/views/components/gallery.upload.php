@props([
    'name' => 'gallery',
    'label' => null,
    'currentImages' => [],
    'hint' => 'يمكنك اختيار أكثر من صورة — JPG, PNG',
])

<div
    x-data="{
        previews: [],
        existingImages: @js($currentImages),
        handleFiles(fileList) {
            this.previews = [];
            Array.from(fileList).forEach(file => {
                this.previews.push({
                    name: file.name,
                    url: URL.createObjectURL(file),
                });
            });
        },
        removeExisting(index) {
            // ملاحظة: هذا يخفي الصورة بصريًا فقط — الحذف الفعلي من قاعدة البيانات
            // يحتاج route/endpoint منفصل، لأن هذه الصور محفوظة بالفعل على السيرفر
            this.existingImages.splice(index, 1);
        }
    }"
>
    @if($label)
        <label class="mb-1.5 block text-sm font-medium text-[#3D342A]">
            {{ $label }}
        </label>
    @endif

    {{-- Existing images already saved (when editing) --}}
    <template x-if="existingImages.length > 0">
        <div class="mb-3 grid grid-cols-3 gap-3 sm:grid-cols-4 md:grid-cols-6">
            <template x-for="(image, index) in existingImages" :key="index">
                <div class="group relative aspect-square overflow-hidden rounded-lg border border-[#B7B5B3]/40">
                    <img :src="image" class="h-full w-full object-cover">
                    <button
                        type="button"
                        @click="removeExisting(index)"
                        class="absolute end-1 top-1 flex h-6 w-6 items-center justify-center rounded-full bg-red-500 text-white opacity-0 transition-opacity group-hover:opacity-100"
                        aria-label="حذف الصورة"
                    >
                        &times;
                    </button>
                </div>
            </template>
        </div>
    </template>

    {{-- Upload zone --}}
    <label
        for="{{ $name }}"
        class="flex cursor-pointer flex-col items-center justify-center gap-2 rounded-lg border-2 border-dashed border-[#B7B5B3] px-4 py-6 text-center transition-colors hover:bg-[#EAEAE9]/50"
    >
        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-[#A38B54]" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909M2.25 12V4.5A2.25 2.25 0 014.5 2.25h15a2.25 2.25 0 012.25 2.25v15a2.25 2.25 0 01-2.25 2.25H4.5A2.25 2.25 0 012.25 19.5V12z" />
        </svg>

        <span class="text-sm text-[#5C5450]">
            اسحب الصور هنا أو <span class="font-medium text-[#A38B54]">اختر عدة صور</span>
        </span>

        @if($hint)
            <span class="text-xs text-[#5C5450]/60">{{ $hint }}</span>
        @endif

        <input
            type="file"
            name="{{ $name }}[]"
            id="{{ $name }}"
            accept="image/*"
            multiple
            @change="handleFiles($event.target.files)"
            class="hidden"
        >
    </label>

    {{-- Preview grid of newly selected images --}}
    <template x-if="previews.length > 0">
        <div class="mt-3 grid grid-cols-3 gap-3 sm:grid-cols-4 md:grid-cols-6">
            <template x-for="(item, index) in previews" :key="index">
                <div class="aspect-square overflow-hidden rounded-lg border border-[#B7B5B3]/40">
                    <img :src="item.url" class="h-full w-full object-cover" :alt="item.name">
                </div>
            </template>
        </div>
    </template>

    @error($name)
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>