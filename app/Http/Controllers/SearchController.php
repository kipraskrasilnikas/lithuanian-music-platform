<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class SearchController extends Controller
{
    public function search() {
        $users = User::all();
        $currentUserId = auth()->id();

        // Filter out the current user from the search results
        $users = $users->reject(function ($user) use ($currentUserId) {
            return $user->id == $currentUserId;
        });

        $counties = config('music_config.counties');
        $genres = config('music_config.genres');
        $specialties = config('music_config.specialties');

        $search_specialty = '';
        $search_genre = '';
        $search_county = '';

        return view('search', compact('users', 'counties', 'genres', 'specialties', 'search_specialty', 'search_genre', 'search_county'));
    }

    public function searchPost (Request $request) {
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
    
        $search_genre = $request->genre;
        if ($search_genre) {
            $query->where('genre', 'like', "%$search_genre%");
        }

        $search_specialty = $request->specialty;
        if ($search_specialty) {
            $query->where('specialty', 'like', "%$search_specialty%");
        }

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
        $search_county = $request->county;
        if ($search_county) {
            $users = $users->filter(function ($user) use ($search_county) {
                return $user->locations()->where('county', 'like', "%$search_county%")->exists();
            });
        }

        return view('search', compact('users', 'search', 'search_genre', 'search_specialty', 'search_county'));
    }
}
