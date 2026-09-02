@props([
    'name',
    'label'      => null,
    'currentUrl' => null,
    'required'   => false,
    'accept'     => 'video/*',
])

<div x-data="{
    preview: '{{ $currentUrl }}',
    fileName: '',
    handleFile(event) {
        const file = event.target.files[0];
        if (file) {
            this.fileName = file.name;
            this.preview  = URL.createObjectURL(file);
        }
    }
}">
    @if($label)
        <label class="mb-1.5 block text-sm font-medium text-[#3D342A]">
            {{ $label }}
            @if($required) <span class="text-red-500">*</span> @endif
        </label>
    @endif

    <div class="space-y-3">
        {{-- Current / Preview --}}
        <template x-if="preview">
            <div class="relative overflow-hidden rounded-xl border border-[#B49C6E]/20">
                <video :src="preview" controls class="h-40 w-full object-cover"></video>
                <button
                    type="button"
                    @click="preview = ''; fileName = ''; $refs.videoInput.value = ''"
                    class="absolute end-2 top-2 rounded-full bg-black/50 p-1 text-background hover:bg-black/70"
                >
                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
        </template>

        {{-- Upload zone --}}
        <label
            class="flex cursor-pointer flex-col items-center justify-center rounded-xl border-2 border-dashed border-[#B49C6E]/40 bg-secondary px-4 py-6 text-center transition hover:bg-secondary/20"
        >
            <svg xmlns="http://www.w3.org/2000/svg" class="mb-1 h-7 w-7 text-[#B49C6E]" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5l4.72-4.72a.75.75 0 011.28.53v11.38a.75.75 0 01-1.28.53l-4.72-4.72M4.5 18.75h9a2.25 2.25 0 002.25-2.25v-9a2.25 2.25 0 00-2.25-2.25h-9A2.25 2.25 0 002.25 7.5v9a2.25 2.25 0 002.25 2.25z" />
            </svg>
            <p class="text-sm text-[#3D342A]/60">
                <span class="font-medium text-[#A38B54]">اختر فيديو</span> أو اسحبه هنا
            </p>
            <p x-show="fileName" x-text="fileName" class="mt-1 text-xs font-medium text-[#A38B54]"></p>
            <input
                x-ref="videoInput"
                type="file"
                name="{{ $name }}"
                accept="{{ $accept }}"
                class="sr-only"
                @change="handleFile($event)"
                {{ $required ? 'required' : '' }}
            >
        </label>
    </div>

    @error($name)
        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
    @enderror
</div>
