@props([
    'eventName' => 'open-preview-modal',
    'title'     => 'معاينة',
])

<div
    x-data="{
        open: false,
        src: '',
        type: 'image',
        caption: '',
        init() {
            window.addEventListener('{{ $eventName }}', (e) => {
                this.src     = e.detail.src ?? '';
                this.type    = e.detail.type ?? 'image';
                this.caption = e.detail.caption ?? '';
                this.open    = true;
            });
        }
    }"
    x-show="open"
    x-cloak
    class="fixed inset-0 z-50 flex items-center justify-center px-4"
    @keydown.escape.window="open = false"
    role="dialog"
    aria-modal="true"
>
    <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" @click="open = false" aria-hidden="true"></div>

    <div
        class="relative w-full max-w-3xl rounded-2xl border border-[#B49C6E]/20 bg-secondary shadow-2xl"
        x-transition:enter="ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="ease-in duration-150"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
    >
        <div class="flex items-center justify-between border-b border-[#B49C6E]/10 px-5 py-3">
            <h3 class="text-sm font-semibold text-[#3D342A]" x-text="caption || '{{ $title }}'"></h3>
            <button type="button" @click="open = false" class="rounded-md p-1 text-[#3D342A]/40 hover:text-[#3D342A]">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <div class="p-4">
            <template x-if="type === 'image'">
                <img :src="src" :alt="caption" class="mx-auto max-h-[70vh] rounded-lg object-contain">
            </template>
            <template x-if="type === 'video'">
                <video :src="src" controls class="mx-auto max-h-[70vh] w-full rounded-lg"></video>
            </template>
            <template x-if="type === 'pdf'">
                <iframe :src="src" class="h-[70vh] w-full rounded-lg border-0"></iframe>
            </template>
        </div>
    </div>
</div>
