<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Song;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class MusicController extends Controller
{
    function music() {
        $songs = Song::orderBy('id', 'desc')->get();

        // Associate genres and moods with each song
        foreach ($songs as $song) {
            $song->genres = $song->genres()->pluck('genre')->toArray();
            $song->moods = $song->moods()->pluck('mood')->toArray();
        }

        // Retrieve all active users sorted by id in descending order
        $users = User::where('status', 1)->orderBy('id', 'desc')->get();

        return view('music', compact('songs', 'users'));
    }
}
