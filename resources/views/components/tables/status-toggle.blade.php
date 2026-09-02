@props([
    'id' => null,
    'isActive' => false,
    'route' => '',
    'activeLabel' => null,
    'inactiveLabel' => null,
])

@php
    $activeText = $activeLabel ?? __('dashboard.common.active');
    $inactiveText = $inactiveLabel ?? __('dashboard.common.inactive');
@endphp

<div
    x-data="{
        active: {{ $isActive ? 'true' : 'false' }},
        loading: false,
        async toggle() {
            if (this.loading) return;
            this.loading = true;
            const previous = this.active;
            this.active = !this.active;
            try {
                const response = await fetch('{{ $route }}', {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({ is_active: this.active })
                });
                const data = await response.json();
                if (!response.ok || !data.success) {
                    this.active = previous;
                } else if (data.is_active !== undefined) {
                    this.active = Boolean(data.is_active);
                }
            } catch (e) {
                this.active = previous;
            } finally {
                this.loading = false;
            }
        }
    }"
    class="inline-flex items-center gap-2"
>
    <button
        type="button"
        @click="toggle()"
        :disabled="loading"
        :class="active ? 'bg-[#A38B54]' : 'bg-[#3D342A]/20'"
        class="relative inline-flex h-6 w-11 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-[#A38B54] focus:ring-offset-2 disabled:opacity-50"
        role="switch"
        :aria-checked="active.toString()"
    >
        <span
            :class="active ? (document.documentElement.dir === 'rtl' ? '-translate-x-5' : 'translate-x-5') : 'translate-x-0'"
            class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-background shadow ring-0 transition duration-200 ease-in-out"
        ></span>
    </button>
    <span
        x-text="active ? '{{ $activeText }}' : '{{ $inactiveText }}'"
        :class="active ? 'bg-[#B49C6E]/30 text-[#A38B54]' : 'bg-red-100 text-red-700'"
        class="rounded-full px-2.5 py-0.5 text-xs font-semibold transition-colors duration-200"
    ></span>
</div>
