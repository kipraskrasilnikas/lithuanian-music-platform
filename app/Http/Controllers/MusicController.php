<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Song;
use Illuminate\Support\Facades\Auth;

class MusicController extends Controller
{
    function music() {
        // galvoju, ar cia iki 5 limituot, ar kitam template irgi pasiimt visas, tik paciam template limituot
        // logiskai uatrodo template'e
        $songs = Song::all();

        // Associate genres and moods with each song
        foreach ($songs as $song) {
            $song->genres = $song->genres()->pluck('genre')->toArray();
            $song->moods = $song->moods()->pluck('mood')->toArray();
        }

        return view('music', compact('songs'));
    }
}
