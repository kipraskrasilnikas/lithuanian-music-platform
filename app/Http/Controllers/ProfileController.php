<?php

namespace App\Http\Controllers;

use App\Models\Specialty;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Location;
use App\Models\Genre;
use App\Models\ArtistMood;
use App\Models\Song;
use App\Models\SongGenre;
use App\Models\SongMood;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    function profile() {
        if (Auth::check()) {
            $user = Auth::user();

            $user_specialties = Specialty::Where('user_id', $user->id)->get();
            $user_genres = Genre::Where('user_id', $user->id)->get();
            $user_moods = ArtistMood::Where('user_id', $user->id)->get();
            $locations = Location::Where('user_id', $user->id)->get();
            $songs = Song::Where('user_id', $user->id)->get();

            // Associate genres and moods with each song
            foreach ($songs as $song) {
                $song->genres = $song->genres()->pluck('genre')->toArray();
                $song->moods = $song->moods()->pluck('mood')->toArray();
            }    

            return view('profile', compact('user', 'user_specialties', 'user_genres', 'user_moods', 'locations', 'songs'));
        }

        return redirect()->route('home');
    }

    function profilePost(Request $request) {
        $request->validate([
            'name'                  => 'required',
            'email'                 => 'required|email',
            'specialties'           => 'required|array', // Validate that specialties is an array and is required
            'specialties.*'         => 'required',       // Validate each value in specialties array is required
            'genres'                => 'required|array', // Validate that specialties is an array and is required
            'genres.*'              => 'required',       // Validate each value in specialties array is required
            'password'              => $request->filled('password') ? 'min:8|confirmed' : '',
            'password_confirmation' => $request->filled('password') ? 'min:8' : '',
            'locations.*.county'    => 'required',
            'locations.*.city'      => 'required',
            'locations.*.address'   => 'nullable',
            'songs.*.title'         => 'required',
            'songs.*.song_url'      => 'required'
        ],
        [
            'name.required'             => 'Vardas yra privalomas!',
            'email.required'            => 'El. paštas yra privalomas!',
            'email.email'               => 'Neteisingas el. pašto formatas!',
            'specialties.required'      => 'Specialybė(-s) yra privaloma(-os)!',
            'specialties.*.required'    => 'Kiekviena specialybė yra privaloma!',
            'genres.required'           => 'Žanras(-ai) yra privalomas(-i)!',
            'genres.*.required'         => 'Kiekvienas žanras yra privaloma!',
            'password.min'              => 'Slaptažodis turi būti bent 8 simbolių ilgio!',
            'password.confirmed'        => 'Slaptažodžiai turi sutapti!',
            'locations.*.county.required'=> 'Apskritis yra privaloma!',
            'locations.*.city.required' => 'Miestas yra privalomas!',
            'songs.*.title.required'    => 'Pavadinimas yra privalomas!',
            'songs.*.song_url.required' => 'Nuoroda yra privaloma!',
        ]);

        // Retrieve the authenticated user
        $user = Auth::user();

        // Update user's information based on form input
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->status = $request->input('status');
        $user->description = $request->input('description');

        // Update password only if it's filled
        if ($request->filled('password')) {
            $user->password = Hash::make($request->input('password'));
        }

        // Save the updated user information
        $user->save();

        $user->specialties()->delete();
        if ($request->specialties) {
            foreach ($request->specialties as $specialtyName) {
                $specialty = new Specialty(['name' => $specialtyName]);
                $specialty->user()->associate($user);
                $specialty->save();
            }
        }

        $user->genres()->delete();
        if ($request->genres) {
            foreach ($request->genres as $genreName) {
                $genre = new Genre(['name' => $genreName]);
                $genre->user()->associate($user);
                $genre->save();
            }
        }
        

        $user->locations()->delete();
        if ($request->locations) {
            foreach ($request->locations as $locationParameters) {
                $location = new Location($locationParameters);
                $location->user()->associate($user);
                $location->save();
            }
        }

        $user->artistMoods()->delete();

        if ($request->moods) {
            foreach ($request->moods as $moodParameters) {
                $artist_mood = new ArtistMood(['mood' => $moodParameters]);
                $artist_mood->user()->associate($user);
                $artist_mood->save();
            }
        }

        $user->songs()->delete();
        if ($request->songs) {
            foreach ($request->songs as $songParameters) {
                $song = new Song([
                    'title' => $songParameters['title'],
                    'song_url'  => $songParameters['song_url'],
                ]);

                $song->user()->associate($user);
                $song->save();

                foreach ($songParameters['genres'] as $genre) {
                    $genre = new SongGenre(['genre' => $genre]);
                    $genre->song()->associate($song);
                    $genre->save();
                }

                foreach ($songParameters['moods'] as $mood) {
                    $mood = new SongMood(['mood' => $mood]);
                    $mood->song()->associate($song);
                    $mood->save();
                }
            }
        }

        // Redirect with success message
        return redirect()->route('profile')->with("success", "Profilis sėkmingai atnaujintas!");
    }
}
