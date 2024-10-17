<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index()
    {
        return Event::all();
    }

    public function store(Request $request)
    {
        $data = $request->validate([

        ]);

        return Event::create($data);
    }

    public function show(Event $event)
    {
        return $event;
    }

    public function update(Request $request, Event $event)
    {
        $data = $request->validate([

        ]);

        $event->update($data);

        return $event;
    }

    public function destroy(Event $event)
    {
        $event->delete();

        return response()->json();
    }
}
