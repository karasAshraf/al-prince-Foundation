@props([
    'name' => 'image',
    'label' => null,
    'currentUrl' => null,
    'accept' => 'image/jpeg,image/png,image/webp,image/gif',
    'hint' => 'JPG, PNG, WEBP حتى 2MB',
])

<div
    x-data="{
        previewUrl: @js($currentUrl),
        handleFileChange(event) {
            const file = event.target.files[0];
            if (file) {
                this.previewUrl = URL.createObjectURL(file);
            }
        },
        removeImage() {
            this.previewUrl = null;
            $refs.fileInput.value = '';
        }
    }"
    class="space-y-3"
>
    @if($label)
        <label class="block text-sm font-medium text-[#3D342A]">{{ $label }}</label>
    @endif

    <div class="relative flex flex-col items-center justify-center rounded-xl border-2 border-dashed border-[#B49C6E]/40 bg-[#EAEAE9] p-4 text-center transition hover:border-[#A38B54]">
        <template x-if="previewUrl">
            <div class="relative w-full overflow-hidden rounded-lg">
                <img :src="previewUrl" class="h-44 w-full object-cover rounded-lg border border-[#B49C6E]/20">
                <button
                    type="button"
                    @click="removeImage()"
                    class="absolute top-2 left-2 rounded-full bg-red-600/80 p-1.5 text-white hover:bg-red-700 transition"
                    title="حذف الصورة"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </template>

        <template x-if="!previewUrl">
            <div class="py-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-10 w-10 text-[#A38B54]/60" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <div class="mt-2 text-sm text-[#3D342A]/70">
                    <span class="font-semibold text-[#A38B54]">اضغط لرفع صورة</span> أو اسحبها هنا
                </div>
                <p class="mt-1 text-xs text-[#3D342A]/50">{{ $hint }}</p>
            </div>
        </template>

        <input
            x-ref="fileInput"
            type="file"
            name="{{ $name }}"
            accept="{{ $accept }}"
            @change="handleFileChange($event)"
            class="absolute inset-0 cursor-pointer opacity-0"
        >
    </div>

    @error($name)
        <p class="mt-1 text-xs font-medium text-red-600">{{ $message }}</p>
    @enderror
</div>
