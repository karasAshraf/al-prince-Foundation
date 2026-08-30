<?php

namespace App\Services;

use App\Models\ContactMessage;

class ContactMessageService extends BaseService
{
    public function list(array $filters = [])
    {
        return ContactMessage::query()
            ->when($filters['is_read'] ?? null, fn($q, $r) => $q->where('is_read', $r))
            ->when($filters['type'] ?? null, fn($q, $t) => $q->where('type', $t))
            ->latest()
            ->paginate(15);
    }

    public function create(array $data): ContactMessage
    {
        $data['ip_address'] = request()->ip();
        return ContactMessage::create($data);
    }

    public function markAsRead(ContactMessage $message): ContactMessage
    {
        $message->update(['is_read' => true]);
        return $message;
    }

    public function delete(ContactMessage $message): bool
    {
        return $message->delete();
    }

    public function unreadCount(): int
    {
        return ContactMessage::unread()->count();
    }
}
