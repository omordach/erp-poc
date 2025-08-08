<?php

namespace Modules\Events\Http\Controllers\Api\V1;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Modules\Events\Models\Event;
use Modules\Events\Http\Resources\EventResource;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $q = Event::query()
            ->when($request->filled('filter[from]'), fn($qq) =>
                $qq->where('starts_at', '>=', $request->input('filter.from')))
            ->when($request->filled('filter[to]'), fn($qq) =>
                $qq->where('starts_at', '<=', $request->input('filter.to')));

        return EventResource::collection($q->paginate(10));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'=>'required|string',
            'starts_at'=>'required|date',
            'ends_at'=>'nullable|date|after_or_equal:starts_at',
            'location'=>'nullable|string',
        ]);
        $event = Event::create($data);
        return (new EventResource($event))->response()->setStatusCode(201);
    }

    public function show(Event $event)
    {
        return new EventResource($event);
    }

    public function update(Request $request, Event $event)
    {
        $data = $request->validate([
            'title'=>'sometimes|string',
            'starts_at'=>'sometimes|date',
            'ends_at'=>'sometimes|date|after_or_equal:starts_at',
            'location'=>'sometimes|string',
        ]);
        $event->update($data);
        return new EventResource($event);
    }

    public function destroy(Event $event)
    {
        $event->delete();
        return response()->json(null, 204);
    }
}
