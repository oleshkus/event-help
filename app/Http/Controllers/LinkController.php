<?php

namespace App\Http\Controllers;

use App\Models\Link;
use Illuminate\Http\Request;

class LinkController extends Controller
{
    public function index()
    {
        return Link::all();
    }

    public function store(Request $request)
    {
        $data = $request->validate([

        ]);

        return Link::create($data);
    }

    public function show(Link $link)
    {
        return $link;
    }

    public function update(Request $request, Link $link)
    {
        $data = $request->validate([

        ]);

        $link->update($data);

        return $link;
    }

    public function destroy(Link $link)
    {
        $link->delete();

        return response()->json();
    }
}
