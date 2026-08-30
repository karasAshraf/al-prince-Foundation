@props([
    'title'         => 'تأكيد العملية',
    'message'       => 'هل أنت متأكد من تنفيذ هذه العملية؟',
    'confirmLabel'  => 'تأكيد',
    'cancelLabel'   => 'إلغاء',
    'eventName'     => 'open-confirm-modal',
])

<div
    x-data="{
        open: false,
        action: '',
        label: '',
        init() {
            window.addEventListener('{{ $eventName }}', (e) => {
                this.action = e.detail.action ?? '';
                this.label  = e.detail.label ?? '';
                this.open   = true;
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
    <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" @click="open = false" aria-hidden="true"></div>

    <div
        class="relative w-full max-w-md rounded-2xl border border-[#B49C6E]/20 bg-[#EAEAE9] p-6 shadow-xl"
        x-transition:enter="ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="ease-in duration-150"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
    >
        <div class="mb-4 flex h-12 w-12 items-center justify-center rounded-full bg-[#EAEAE9]/60">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-[#A38B54]" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>

        <h3 class="mb-1 text-base font-semibold text-[#3D342A]">{{ $title }}</h3>
        <p class="mb-6 text-sm text-[#3D342A]/60">{{ $message }}</p>

        <div class="flex items-center justify-end gap-3">
            <button
                type="button"
                @click="open = false"
                class="rounded-lg border border-[#B49C6E]/40 px-4 py-2 text-sm font-medium text-[#3D342A] transition hover:bg-[#EAEAE9]/40"
            >
                {{ $cancelLabel }}
            </button>
            <form :action="action" method="POST" class="inline">
                @csrf
                <button
                    type="submit"
                    class="rounded-lg bg-[#A38B54] px-4 py-2 text-sm font-medium text-[#EAEAE9] transition hover:bg-[#A38B54]/90"
                >
                    {{ $confirmLabel }}
                </button>
            </form>
        </div>
    </div>
</div>
