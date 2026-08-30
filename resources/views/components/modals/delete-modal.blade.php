<div
    x-data="{
        open: false,
        action: '',
        label: '',
        init() {
            window.addEventListener('open-delete-modal', (e) => {
                this.action = e.detail.action;
                this.label  = e.detail.label;
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
    :aria-label="'{{ __('dashboard.common.delete') }} ' + label"
>
    {{-- Backdrop --}}
    <div
        class="absolute inset-0 bg-black/40 backdrop-blur-sm"
        x-transition:enter="ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        @click="open = false"
        aria-hidden="true"
    ></div>

    {{-- Dialog Panel --}}
    <div
        class="relative w-full max-w-md rounded-2xl border border-[#B49C6E]/20 bg-[#EAEAE9] p-6 shadow-xl"
        x-transition:enter="ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="ease-in duration-150"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
    >
        {{-- Icon --}}
        <div class="mb-4 flex h-12 w-12 items-center justify-center rounded-full bg-red-100">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
            </svg>
        </div>

        {{-- Content --}}
        <h3 class="mb-1 text-base font-semibold text-[#3D342A]">{{ __('dashboard.common.confirm_delete') }}</h3>
        <p class="mb-6 text-sm text-[#3D342A]/60">
            {{ __('dashboard.common.confirm_delete_message') }}
            <strong class="font-semibold text-[#3D342A]" x-text="label"></strong>
        </p>

        {{-- Actions --}}
        <div class="flex items-center justify-end gap-3">
            <button
                type="button"
                @click="open = false"
                class="rounded-lg border border-[#B49C6E]/40 px-4 py-2 text-sm font-medium text-[#3D342A] transition hover:bg-[#EAEAE9]/40 focus:outline-none focus:ring-2 focus:ring-[#A38B54]/30"
            >
                {{ __('dashboard.common.cancel') }}
            </button>

            <form :action="action" method="POST" class="inline">
                @csrf
                @method('DELETE')
                <button
                    type="submit"
                    class="rounded-lg bg-red-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500/50"
                >
                    {{ __('dashboard.common.delete') }}
                </button>
            </form>
        </div>
    </div>
</div>
