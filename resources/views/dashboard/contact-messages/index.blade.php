@extends('layouts.app')

@section('title', __('dashboard.contact_messages.title'))

@php
    $breadcrumbs = [['label' => __('dashboard.contact_messages.title'), 'url' => null]];
@endphp

@section('content')

    <div class="mb-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <h1 class="text-xl font-bold text-[#3D342A]">{{ __('dashboard.contact_messages.title') }}</h1>
    </div>

    @if($messages->isEmpty())
        <x-tables.empty-state
            title="{{ __('dashboard.contact_messages.no_messages') }}"
            message="{{ __('dashboard.common.empty_state') }}"
        />
    @else
        <div class="overflow-x-auto rounded-xl border border-[#B49C6E]/20 bg-secondary">
            <table class="w-full text-start">
                <thead class="bg-secondary/30">
                    <tr>
                        <th class="px-4 py-3 text-start text-xs font-semibold text-[#3D342A]/60">{{ __('dashboard.contact_messages.sender_name') }}</th>
                        <th class="px-4 py-3 text-start text-xs font-semibold text-[#3D342A]/60">{{ __('dashboard.contact_messages.sender_email') }}</th>
                        <th class="px-4 py-3 text-start text-xs font-semibold text-[#3D342A]/60">{{ __('dashboard.contact_messages.sender_phone') }}</th>
                        <th class="px-4 py-3 text-start text-xs font-semibold text-[#3D342A]/60">{{ __('dashboard.contact_messages.subject') }}</th>
                        <th class="px-4 py-3 text-start text-xs font-semibold text-[#3D342A]/60">{{ __('dashboard.common.status') }}</th>
                        <th class="px-4 py-3 text-start text-xs font-semibold text-[#3D342A]/60">{{ __('dashboard.common.created_at') }}</th>
                        <th class="px-4 py-3 text-end text-xs font-semibold text-[#3D342A]/60">{{ __('dashboard.common.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($messages as $msg)
                        <tr class="border-b border-[#B49C6E]/10 hover:bg-secondary/10">
                            <td class="px-4 py-3 text-sm font-medium text-[#3D342A]">{{ $msg->name }}</td>
                            <td class="px-4 py-3 text-sm text-[#3D342A]/80">{{ $msg->email }}</td>
                            <td class="px-4 py-3 text-sm text-[#3D342A]/80">{{ $msg->phone ?? '—' }}</td>
                            <td class="px-4 py-3 text-sm text-[#3D342A]">{{ $msg->subject ?? '—' }}</td>
                            <td class="px-4 py-3 text-sm">
                                <span @class([
                                    'rounded-full px-2.5 py-0.5 text-xs font-semibold',
                                    'bg-[#B49C6E]/30 text-[#A38B54]' => $msg->is_read,
                                    'bg-amber-100 text-amber-800' => !$msg->is_read,
                                ])>
                                    {{ $msg->is_read ? __('dashboard.contact_messages.read') : __('dashboard.contact_messages.unread') }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-sm text-[#3D342A]/60">{{ $msg->created_at->format('Y-m-d H:i') }}</td>
                            <td class="px-4 py-3 text-end">
                                <x-tables.table-actions
                                    :show-url="route('dashboard.contact-messages.show', $msg)"
                                    :delete-action="route('dashboard.contact-messages.destroy', $msg)"
                                    :item-label="$msg->name"
                                />
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <x-tables.pagination :paginator="$messages" />
    @endif

    <x-modals.delete-modal />

@endsection
