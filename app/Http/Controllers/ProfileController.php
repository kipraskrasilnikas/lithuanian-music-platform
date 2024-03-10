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

            // Define the list of Lithuanian counties
            $counties = [
                'Alytus',
                'Kaunas',
                'Klaipėda',
                'Marijampolė',
                'Panevėžys',
                'Šiauliai',
                'Tauragė',
                'Telšiai',
                'Utena',
                'Vilnius'
            ];

            $genres = [
                'Alternatyva',
                'Džiazas ir Bliuzas',
                'Elektroninė muzika',
                'Folkas, dainavimas ir dainų rašymas',
                'Garso takelis',
                'Hip-hopas ir Repas',
                'Indie',
                'Kantri',
                'Klasika',
                'Latin',
                'Metalas',
                'Pasaulinis',
                'Pianinas',
                'Popas',
                'R&B ir Siela',
                'Regė',
                'Reggaetonas',
                'Rokas',
                'Šokio muzika ir EDM',
                'Techno',
                'Trance',
                'Trapas',
                'Triphopas',
            ];
                        
                        
            $specialties = [
                'Vokalistas',
                'Instrumentalistas', // Bendra parinktis muzikantėms, kurios pagrindiniu būdu groja instrumentais
                'DJ',
                'Prodiuseris',
                'Garso inžinierius',
                'Mixinimo inžinierius',
                'Masterinimo inžinierius',
                'Kompozitorius',
                'Dainų autorius',
                'Reperis',
                'Foninis vokalistas',
                'Bytbokseris'
            ];
                        
            return view('profile', compact('user', 'counties', 'genres', 'specialties', 'locations'));
        }

        return redirect()->route('home');
    }

    function profilePost(Request $request) {
        $request->validate([
            'name'                  => 'required',
            'email'                 => 'required|email',
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
