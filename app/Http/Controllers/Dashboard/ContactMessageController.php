<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use App\Services\ContactMessageService;
use Illuminate\Http\Request;

class ContactMessageController extends Controller
{
    public function __construct(protected ContactMessageService $service) {}

    public function index(Request $request)
    {
        $messages = $this->service->list($request->only('is_read', 'type'));
        return view('dashboard.contact-messages.index', compact('messages'));
    }

    public function show(ContactMessage $contactMessage)
    {
        $this->service->markAsRead($contactMessage);
        return view('dashboard.contact-messages.show', ['message' => $contactMessage]);
    }

    public function destroy(ContactMessage $contactMessage)
    {
        $this->service->delete($contactMessage);
        return back()->with('success', 'تم الحذف بنجاح');
    }
}