@props([
    'name',
    'label' => null,
    'selectedUrl' => null,
])

<div
    x-data="{
        open: false,
        selected: @js($selectedUrl),
        mediaItems: [],
        loading: false,
        loadMedia() {
            this.loading = true;
            fetch('{{ route('dashboard.media.list') }}')
                .then(res => res.json())
                .then(data => { this.mediaItems = data; this.loading = false; });
        },
        choose(item) {
            this.selected = item.url;
            this.open = false;
        }
    }"
>
    @if($label)
        <label class="mb-1.5 block text-sm font-medium text-[#3D342A]">
            {{ $label }}
        </label>
    @endif

    {{-- Current selection preview --}}
    <div class="flex items-center gap-3">
        <div class="flex h-20 w-20 shrink-0 items-center justify-center overflow-hidden rounded-lg border border-[#B49C6E]/30 bg-secondary/20">
            <template x-if="selected">
                <img :src="selected" class="h-full w-full object-cover">
            </template>
            <template x-if="!selected">
                <span class="text-xs text-[#3D342A]/40">لا يوجد</span>
            </template>
        </div>

        <button
            type="button"
            @click="open = true; loadMedia()"
            class="rounded-lg border border-[#A38B54] px-4 py-2 text-sm font-medium text-[#A38B54] hover:bg-secondary/40"
        >
            اختيار من الملفات
        </button>
    </div>

    {{-- Hidden input that actually gets submitted --}}
    <input type="hidden" name="{{ $name }}" x-model="selected">

    {{-- ============ MODAL ============ --}}
    <div
        x-show="open"
        x-cloak
        class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4"
    >
        <div
            @click.outside="open = false"
            x-transition
            class="max-h-[80vh] w-full max-w-2xl overflow-hidden rounded-xl bg-secondary shadow-xl"
        >
            {{-- Modal header --}}
            <div class="flex items-center justify-between border-b border-[#B49C6E]/30 px-5 py-3">
                <h3 class="text-sm font-semibold text-[#3D342A]">اختر صورة</h3>
                <button @click="open = false" class="text-[#3D342A]/50 hover:text-[#3D342A]" aria-label="إغلاق">
                    &times;
                </button>
            </div>

            {{-- Modal body: media grid --}}
            <div class="max-h-[60vh] overflow-y-auto p-5">
                <template x-if="loading">
                    <p class="text-center text-sm text-[#3D342A]/50">جارٍ التحميل...</p>
                </template>

                <template x-if="!loading && mediaItems.length === 0">
                    <p class="text-center text-sm text-[#3D342A]/50">لا توجد ملفات مرفوعة بعد</p>
                </template>

                <div class="grid grid-cols-3 gap-3 sm:grid-cols-4">
                    <template x-for="item in mediaItems" :key="item.id">
                        <button
                            type="button"
                            @click="choose(item)"
                            class="aspect-square overflow-hidden rounded-lg border-2 border-transparent hover:border-[#A38B54]"
                        >
                            <img :src="item.url" class="h-full w-full object-cover">
                        </button>
                    </template>
                </div>
            </div>
        </div>
    </div>
</div>