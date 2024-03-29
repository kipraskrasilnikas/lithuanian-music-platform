<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Location;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;


class ProfileController extends Controller
{
    function profile() {
        if (Auth::check()) {
            $user = Auth::user();

            $locations = Location::Where('user_id', $user->id)->get();

            $counties = config('music_config.counties');
            $genres = config('music_config.genres');
            $specialties = config('music_config.specialties');

                        
            return view('profile', compact('user', 'counties', 'genres', 'specialties', 'locations'));
        }

        return redirect()->route('home');
    }

    function profilePost(Request $request) {
        $request->validate([
            'name'                  => 'required',
            'email'                 => 'required|email',
            'specialty'             => 'required',
            'genre'                 => 'nullable',
            'password'              => $request->filled('password') ? 'min:8|confirmed' : '',
            'password_confirmation' => $request->filled('password') ? 'min:8' : '',
            'locations.*.county'    => 'required',
            'locations.*.city'      => 'required',
            'locations.*.address'   => 'nullable',
        ],
        [
            'locations.*.county' => 'The County field is required!',
            'locations.*.city' => 'The City field is required!',
        ]);

        // Retrieve the authenticated user
        $user = Auth::user();

        // Update user's information based on form input
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->specialty = $request->input('specialty');
        $user->genre = $request->input('genre');

        // Update password only if it's filled
        if ($request->filled('password')) {
            $user->password = Hash::make($request->input('password'));
        }

        // Save the updated user information
        $user->save();

        $location = new Location();

        $user->locations()->delete();

        foreach ($request->locations as $locationParameters) {
            $location = new Location($locationParameters);
            $location->user()->associate($user);
            $location->save();
        }

        // Redirect with success message
        return redirect()->route('profile')->with("success", "Profile updated successfully!");
    }
}
