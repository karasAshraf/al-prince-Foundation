@props(['errors' => null])

@php $errList = $errors ?? $errors ?? (isset($__bag) ? $__bag : null); @endphp

@if($errors && $errors->any())
    <div class="mb-5 rounded-xl border border-red-200 bg-red-50 p-4">
        <div class="mb-2 flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0 text-red-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
            </svg>
            <p class="text-sm font-semibold text-red-700">يرجى تصحيح الأخطاء التالية:</p>
        </div>
        <ul class="list-inside list-disc space-y-1 text-sm text-red-600">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
