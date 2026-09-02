@props(['headers' => []])

<div class="overflow-x-auto rounded-xl border border-[#B49C6E]/20 bg-secondary">
    <table class="w-full text-start">
        <thead class="bg-secondary/30">
            <tr>
                @foreach($headers as $header)
                    <th class="px-4 py-3 text-start text-xs font-semibold uppercase tracking-wide text-[#3D342A]/60">
                        {{ $header }}
                    </th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            {{ $slot }}
        </tbody>
    </table>
</div>