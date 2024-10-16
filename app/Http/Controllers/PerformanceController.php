<?php

namespace App\Http\Controllers;

use App\Models\Performance;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PerformanceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return Performance::all();
    }

    public function store(Request $request, $eventId)
    {
        $event = Event::where('id', $eventId)->where('user_id', Auth::id())->firstOrFail();

        $request->validate([
            'title' => 'required|string|max:255',
            'expected_duration' => 'required|integer|min:1',
            'description' => 'string|max:255',
            'location' => 'nullable|string|max:255',
            'participants' => 'nullable|array',
        ]);

        $performance = new Performance($request->all());
        $performance->event_id = $event->id;
        $performance->save();

        return response()->json($performance, 201);
    }

    public function startPerformance(Performance $performance)
    {
        $performance->actual_start_time = now();
        $performance->save();

        return response()->json(['message' => 'Performance started', 'performance' => $performance], 200);
    }

    public function endPerformance(Performance $performance)
    {
        $performance->actual_end_time = now();
        $performance->delay = $performance->calculateDelay();
        $performance->save();

        return response()->json(['message' => 'Performance ended', 'performance' => $performance], 200);
    }

    public function show(Performance $performance)
    {
        return response()->json($performance);
    }

    public function update(Request $request, Performance $performance)
    {
        $performance->update($request->all());
        return response()->json($performance, 200);
    }

    public function destroy(Performance $performance)
    {
        $performance->delete();
        return response()->json(['message' => 'Performance deleted successfully'], 200);
    }
}
