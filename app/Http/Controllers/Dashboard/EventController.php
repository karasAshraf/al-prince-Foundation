<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\EventRequest;
use App\Models\Event;
use App\Services\EventService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class EventController extends Controller
{
    public function __construct(protected EventService $service) {}

    public function index(Request $request)
    {
        $events = $this->service->list($request->only('is_active'));
        return view('dashboard.events.index', compact('events'));
    }

    public function create()
    {
        return view('dashboard.events.create', ['eventItem' => new Event()]);
    }

    public function store(EventRequest $request)
    {
        $this->service->create($request->validated());
        return redirect()->route('dashboard.events.index')->with('success', 'تم إضافة الفعالية بنجاح');
    }

    public function show(Event $event)
    {
        return view('dashboard.events.show', compact('event'));
    }

    public function edit(Event $event)
    {
        $event->load('seoMeta');
        return view('dashboard.events.edit', compact('event'));
    }

    public function update(EventRequest $request, Event $event)
    {
        $this->service->update($event, $request->validated());
        return redirect()->route('dashboard.events.index')->with('success', 'تم تحديث الفعالية بنجاح');
    }

    public function destroy(Event $event)
    {
        $this->service->delete($event);
        return back()->with('success', 'تم حذف الفعالية بنجاح');
    }

    public function toggleStatus(Request $request, Event $event)
    {
        $newStatus = $request->has('is_active') ? $request->boolean('is_active') : !$event->is_active;
        $event->is_active = $newStatus;
        $event->save();

        Cache::forget('home.active_events');

        return response()->json([
            'success'   => true,
            'is_active' => (bool) $event->is_active,
            'message'   => 'تم تحديث الحالة بنجاح',
        ]);
    }
}
