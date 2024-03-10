<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class SearchController extends Controller
{
    public function index() {
        $users = User::all();
        $currentUserId = auth()->id();

        // Filter out the current user from the search results
        $users = $users->reject(function ($user) use ($currentUserId) {
            return $user->id == $currentUserId;
        });

        $counties = config('music_config.counties');
        $genres = config('music_config.genres');
        $specialties = config('music_config.specialties');

        return view('search', compact('users', 'counties', 'genres', 'specialties'));
    }

    public function search (Request $request) {
        $search = $request->search;
        $currentUserId = auth()->id(); // Get the ID of the current authenticated user

        $query = User::query();

        if ($search) {
            $query->where(function ($query) use ($search) {
                $query->where('name', 'like', "%$search%")
                    ->orWhere('genre', 'like', "%$search%")
                    ->orWhere('specialty', 'like', "%$search%");
            });
        }
    
        $genre = $request->genre;
        if ($genre) {
            $query->where('genre', 'like', "%$genre%");
        }

        $specialty = $request->specialty;
        if ($specialty) {
            $query->where('specialty', 'like', "%$specialty%");
        }

        $county = $request->county;

        if ($search) {
            $query->orWhereHas('locations', function ($query) use ($search) {
                $query->where('county', 'like', "%$search%")
                    ->orWhere('city', 'like', "%$search%");
            });
        } 

        // Get the search results
        $users = $query->get();
    
        // Filter out the current user from the search results
        $users = $users->reject(function ($user) use ($currentUserId) {
            return $user->id == $currentUserId;
        });
        
        // Filter by county if specified
        $county = $request->county;
        if ($county) {
            $users = $users->filter(function ($user) use ($county) {
                return $user->locations()->where('county', 'like', "%$county%")->exists();
            });
        }

        return view('search', compact('users', 'search'));
    }
}
