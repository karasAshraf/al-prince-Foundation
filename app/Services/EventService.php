<?php

namespace App\Services;

use App\Models\Event;

class EventService extends ActivityService
{
    protected string $modelClass = Event::class;
    protected string $cacheKey = 'home.active_events';
}
