<?php

namespace App\Http\Controllers;

use App\Models\UserSpecialty;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserLocation;
use App\Models\UserGenre;
use App\Models\UserMood;
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

            $user_specialties = UserSpecialty::Where('user_id', $user->id)->get();
            $user_genres = UserGenre::Where('user_id', $user->id)->get();
            $user_moods = UserMood::Where('user_id', $user->id)->get();
            $locations = UserLocation::Where('user_id', $user->id)->get();
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
            'songs.*.original_url'  => 'required'
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
            'songs.*.original_url.required' => 'Nuoroda yra privaloma!',
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

        $base64_image       = $request->base64_image;

        if ($base64_image) {
            list($type, $data)  = explode(';', $base64_image);
            list(, $data)       = explode(',', $data);
            $data               = base64_decode($data);
            $thumb_name         = "thumb_".date('YmdHis').'.png';
            $thumb_path         = public_path("images/" . $thumb_name);
            file_put_contents($thumb_path, $data);

            $user->avatar = $thumb_name;
        }

        // Save the updated user information
        $user->save();

        $user->specialties()->delete();
        if ($request->specialties) {
            foreach ($request->specialties as $specialtyName) {
                $specialty = new UserSpecialty(['name' => $specialtyName]);
                $specialty->user()->associate($user);
                $specialty->save();
            }
        }

        $user->genres()->delete();
        if ($request->genres) {
            foreach ($request->genres as $genreName) {
                $genre = new UserGenre(['name' => $genreName]);
                $genre->user()->associate($user);
                $genre->save();
            }
        }

        $user->locations()->delete();
        if ($request->locations) {
            foreach ($request->locations as $locationParameters) {
                $location = new UserLocation($locationParameters);
                $location->user()->associate($user);
                $location->save();
            }
        }

        $user->moods()->delete();

        if ($request->moods) {
            foreach ($request->moods as $moodParameters) {
                $user_mood = new UserMood(['mood' => $moodParameters]);
                $user_mood->user()->associate($user);
                $user_mood->save();
            }
        }

        $user->songs()->delete();
        if ($request->songs) {
            foreach ($request->songs as $songParameters) {
                $videoId = $this->extractYouTubeVideoId($songParameters['original_url']);

                // Create the embedded URL if a valid video ID is found
                $embeddedUrl = null;
                if ($videoId !== null) {
                    $embeddedUrl = "https://www.youtube.com/embed/{$videoId}";
                }

                $song = new Song([
                    'title' => $songParameters['title'],
                    'original_url'  => $songParameters['original_url'],
                    'embedded_url'  => $embeddedUrl
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

    private function extractYouTubeVideoId($url) {
        $videoId = null;
        if (strpos($url, 'youtube.com') !== false) {
            $query = parse_url($url, PHP_URL_QUERY);
            parse_str($query, $params);
            if (isset($params['v'])) {
                $videoId = $params['v'];
            }
        } elseif (strpos($url, 'youtu.be') !== false) {
            $path = parse_url($url, PHP_URL_PATH);
            $videoId = ltrim($path, '/');
            if (strpos($videoId, '?') !== false) {
                $videoId = substr($videoId, 0, strpos($videoId, '?'));
            }
        }
        return $videoId;
    }

    public function show(string $id) {
        $user = User::find($id);

        // nes view, o ne route lmao
        return view('profile.show')->with('user', $user);
    }
}
