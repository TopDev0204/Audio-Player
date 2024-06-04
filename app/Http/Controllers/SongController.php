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

    public function playOrStop(Request $request, $id)
    {
        $status = $request->input('status');
        $song = Song::findOrFail($id);
        $song->playing = !$status;
        $song->save();

        return response()->json($song);
    }
}
