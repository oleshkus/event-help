<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Inertia\Inertia;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $events = Event::where('user_id', Auth::id())->with('performances')->get();
        return response()->json($events);

//        $events = Event::where('user_id', Auth::id())->with('performances')->get();
//        return Inertia::render('Events/Index', [
//            'events' => $events,
//        ]);
    }

    public function test()
    {
        $events = Event::where('user_id', Auth::id())->with('performances')->get();
        return Inertia::render('Events', [
            'events' => $events,
            'logged_in' => auth()->check(),
            'user' => auth()->user(),
        ]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'start_time' => 'required|date',
        ]);

        $event = Event::create([
            'title' => $request->title,
            'start_time' => $request->start_time,
            'user_id' => Auth::id(),
        ]);

        return response()->json($event, 201);
    }

    public function show(Event $event)
    {
        return Inertia::render('Event', [
            'event' => $event,
        ]);
    }

    public function update(Request $request, Event $event)
    {
        $event->update($request->all());
        return response()->json($event, 200);
    }

    public function destroy(Event $event)
    {
        $event->delete();
        return response()->json(['message' => 'Event deleted successfully'], 200);
    }

    public function destroy_all()
    {
        Event::where('user_id', Auth::id())->delete();
        return response()->json(['message' => 'All events deleted successfully'], 200);
    }
}
