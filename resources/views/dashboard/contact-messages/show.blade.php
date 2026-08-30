@extends('layouts.app')

@section('title', __('dashboard.contact_messages.show'))

@php
    $breadcrumbs = [
        ['label' => __('dashboard.contact_messages.title'), 'url' => route('dashboard.contact-messages.index')],
        ['label' => __('dashboard.contact_messages.show'), 'url' => null],
    ];
@endphp

@section('content')

    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-xl font-bold text-[#3D342A]">{{ $message->subject ?: '—' }}</h1>
            <p class="text-xs text-[#3D342A]/60 mt-0.5">{{ $message->name }} ({{ $message->email }})</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="mailto:{{ $message->email }}">
                <x-buttons.primary>{{ __('dashboard.contact_messages.reply') }} ✉</x-buttons.primary>
            </a>
            <a href="{{ route('dashboard.contact-messages.index') }}">
                <x-buttons.secondary>← {{ __('dashboard.common.back') }}</x-buttons.secondary>
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        <div class="space-y-5 lg:col-span-2">
            <div class="rounded-xl border border-[#B49C6E]/20 bg-[#EAEAE9] p-6 shadow-sm space-y-4">
                <div class="flex items-center justify-between border-b border-[#B49C6E]/20 pb-4">
                    <div class="flex items-center gap-3">
                        <div class="flex h-10 w-10 items-center justify-center rounded-full bg-[#A38B54]/10 text-[#A38B54] font-bold">
                            {{ mb_substr($message->name, 0, 1) }}
                        </div>
                        <div>
                            <h3 class="font-bold text-[#3D342A]">{{ $message->name }}</h3>
                            <p class="text-xs text-[#3D342A]/60">{{ $message->email }} @if($message->phone) | {{ $message->phone }} @endif</p>
                        </div>
                    </div>
                </div>

                <div class="py-2">
                    <h4 class="text-xs font-semibold text-[#3D342A]/60 mb-2">{{ __('dashboard.contact_messages.message') }}:</h4>
                    <div class="rounded-xl border border-[#B49C6E]/30 bg-[#EAEAE9]/10 p-4 text-sm text-[#3D342A] leading-relaxed whitespace-pre-line">
                        {{ $message->message }}
                    </div>
                </div>
            </div>
        </div>

        <div class="space-y-5">
            <div class="rounded-xl border border-[#B49C6E]/20 bg-[#EAEAE9] p-5 shadow-sm">
                <h3 class="mb-4 text-sm font-semibold text-[#3D342A] border-b border-[#B49C6E]/20 pb-2">{{ __('dashboard.common.details') }}</h3>
                <dl class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <dt class="text-[#3D342A]/60">{{ __('dashboard.contact_messages.sender_phone') }}</dt>
                        <dd class="font-medium text-[#3D342A]">{{ $message->phone ?: '—' }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-[#3D342A]/60">IP</dt>
                        <dd class="font-medium text-[#3D342A]">{{ $message->ip_address ?: '—' }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-[#3D342A]/60">{{ __('dashboard.common.created_at') }}</dt>
                        <dd class="font-medium text-[#3D342A]">{{ $message->created_at?->format('Y-m-d H:i') }}</dd>
                    </div>
                </dl>
            </div>
        </div>
    </div>

@endsection
