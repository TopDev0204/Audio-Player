<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\Song;

class SongController extends Controller
{
    //
    public function get_all(Request $request)
    {
        $songs = Song::all();
        return response()->json($songs);
    }

    public function play($id)
    {
        $song = Song::findOrFail($id);
        // Update the song state (playing or not)
        return response()->json($song);
    }
}
