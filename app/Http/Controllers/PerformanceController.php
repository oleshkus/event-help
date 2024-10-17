<?php

namespace App\Http\Controllers;

use App\Models\Performance;
use Illuminate\Http\Request;

class PerformanceController extends Controller
{
    public function index()
    {
        return Performance::all();
    }

    public function store(Request $request)
    {
        $data = $request->validate([

        ]);

        return Performance::create($data);
    }

    public function show(Performance $performance)
    {
        return $performance;
    }

    public function update(Request $request, Performance $performance)
    {
        $data = $request->validate([

        ]);

        $performance->update($data);

        return $performance;
    }

    public function destroy(Performance $performance)
    {
        $performance->delete();

        return response()->json();
    }
}
