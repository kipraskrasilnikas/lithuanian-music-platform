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

            return view('profile', compact('user', 'counties'));
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
            // 'locations.*.city'      => 'required',
            'locations.*.address'   => 'nullable',
            'locations.*.postcode'  => 'nullable',
        ],
        [
            'locations.*.county' => 'The County field is required!',
            // 'locations.*.city' => 'The City field is required!',
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

        foreach ($request->locations as $location_parameters) {
            $location = new Location();
            foreach ($location_parameters as $key => $value) {
                // key - county
                // value - Kaunas
                $location->$key = $value;
            }

            $location->user()->associate($user);
            $location->save();
        }

        // Redirect with success message
        return redirect()->route('profile')->with("success", "Profile updated successfully!");
    }
}
