<?php

namespace App\Http\Controllers;

use App\Models\Event;

class EventFrontController extends Controller
{
    public function index()
    {
        $events = Event::active()->with('media')->paginate(12);
        return view('frontend.events.index', compact('events'));
    }

    public function show(Event $event)
    {
        abort_if(!$event->is_active, 404);
        $event->load('media');
        return view('frontend.events.show', compact('event'));
    }
}
