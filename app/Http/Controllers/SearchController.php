<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class SearchController extends Controller
{
    public function search() {
        $query = User::query();
        $currentUserId = auth()->id();

        // Filter out current user
        $query->where('users.id', '!=', $currentUserId);

        // Get the search results
        $users = $query->groupBy('id')->paginate(10);

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

        // Select statement with aliases
        $query->select('users.*', 'genres.name as genre', 'specialties.name as specialty', 'locations.county', 'locations.city');

        // Join the genre table
        $query->leftJoin('genres', 'users.id', '=', 'genres.user_id');

        // Join the specialty table
        $query->leftJoin('specialties', 'users.id', '=', 'specialties.user_id');

        // Join the locations table
        $query->leftJoin('locations', 'users.id', '=', 'locations.user_id');

        $query->where(function ($query) use ($search, $request) {
            if ($search) {
                $query->where('users.name', 'like', "%$search%")
                    ->orWhere('genres.name', 'like', "%$search%")
                    ->orWhere('specialties.name', 'like', "%$search%")
                    ->orWhere('locations.county', 'like', "%$search%")
                    ->orWhere('locations.city', 'like', "%$search%");    
            }
        });

        // Filter by genre
        $search_genres = array_filter($request->genres ?? []);
        if ($search_genres) {
            $query->whereIn('genres.name', $search_genres);
        }

        // Filter by specialty
        $search_specialties = array_filter($request->specialties ?? []);
        if ($search_specialties) {
            $query->whereIn('specialties.name', $search_specialties);
        }

        // Filter by county if specified
        $search_counties = array_filter($request->counties ?? []);
        if ($search_counties) {
            $query->whereIn('locations.county', $search_counties);
        }

        // Filter out current user
        $query->where('users.id', '!=', $currentUserId);

        // Get the search results
        $users = $query->groupBy('id')->paginate(10);

        return view('search', compact('users', 'search', 'search_genres', 'search_specialties', 'search_counties'));
    }
}
