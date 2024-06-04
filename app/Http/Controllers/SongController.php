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
        foreach($songs as $song) {
            $song->playing = false;
            $song->save();
        }
        return response()->json($songs);
    }

    public function playOrStop(Request $request, $id)
    {
        $currentId = $request->input('currentId');
        if($currentId !== null && $currentId !== $id*1) {
            Song::where('id', $currentId)->update(array('playing' => false));
        };
        $song = Song::findOrFail($id);
        if ($song->playing) {
            $song->playing = false;
        } else {
            $song->playing = true;
        }
        $song->save();

        return response()->json($song);
    }
}
