<?php

namespace App\Http\Controllers;

use App\Models\Specialty;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Location;
use App\Models\Genre;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    function profile() {
        if (Auth::check()) {
            $user = Auth::user();

            $user_specialties = Specialty::Where('user_id', $user->id)->get();
            $user_genres = Genre::Where('user_id', $user->id)->get();
            $locations = Location::Where('user_id', $user->id)->get();
                        
            return view('profile', compact('user', 'user_specialties', 'user_genres', 'locations'));
        }

        return redirect()->route('home');
    }

    function profilePost(Request $request) {
        $request->validate([
            'name'                  => 'required',
            'email'                 => 'required|email',
            'specialties'           => 'required|array', // Validate that specialties is an array and is required
            'specialties.*'         => 'required',       // Validate each value in specialties array is required
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
        $user->status = $request->input('status');
        $user->description = $request->input('description');

        // Update password only if it's filled
        if ($request->filled('password')) {
            $user->password = Hash::make($request->input('password'));
        }

        // Save the updated user information
        $user->save();

        $specialty = new Specialty();
        $user->specialties()->delete();
        foreach ($request->specialties as $specialtyName) {
            $specialty = new Specialty(['name' => $specialtyName]);
            $specialty->user()->associate($user);
            $specialty->save();
        }

        $genre = new Genre();
        $user->genres()->delete();
        foreach ($request->genres as $genreName) {
            $genre = new Genre(['name' => $genreName]);
            $genre->user()->associate($user);
            $genre->save();
        }

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
