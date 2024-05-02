<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Resource;
use App\Models\Song;
use Illuminate\Support\Facades\Auth;

class SearchController extends Controller
{
    public function search() {
        $query = User::query();
        $currentUserId = auth()->id();

        // Filter out current user
        $query->where('users.id', '!=', $currentUserId);

        // Filter inactive users
        $query->where('status', 1);

        // Sort by id in descending order
        $query->orderByDesc('id');

        // Get the search results
        $users = $query->groupBy('id')->paginate(10);

        return view('search', compact('users'));
    }

    public function searchPost (Request $request) {
        $search = $request->search;
        $currentUserId = auth()->id(); // Get the ID of the current authenticated user

        $query = User::query();

        // Select statement with aliases
        $query->select('users.*', 'user_genres.name as genre', 'specialties.name as specialty', 'locations.county', 'locations.city');

        // Join the genre table
        $query->leftJoin('user_genres', 'users.id', '=', 'user_genres.user_id');

        // Join the specialty table
        $query->leftJoin('specialties', 'users.id', '=', 'specialties.user_id');

        // Join the locations table
        $query->leftJoin('locations', 'users.id', '=', 'locations.user_id');

        // Join the artist moods table
        $query->leftJoin('user_moods', 'users.id', '=', 'user_moods.user_id');

        $query->where(function ($query) use ($search) {
            if ($search) {
                $query->where('users.name', 'like', "%$search%")
                    ->orWhere('user_genres.name', 'like', "%$search%")
                    ->orWhere('specialties.name', 'like', "%$search%")
                    ->orWhere('locations.county', 'like', "%$search%")
                    ->orWhere('locations.city', 'like', "%$search%")   
                    ->orWhere('user_moods.mood', 'like', "%$search%");    
            }
        });

        // Filter by genre
        $search_genres = array_filter($request->genres ?? []);
        if ($search_genres) {
            $query->whereIn('user_genres.name', $search_genres);
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

        // Filter by artist moods if specified
        $search_moods = array_filter($request->moods ?? []);
        if ($search_moods) {
            $query->whereHas('moods', function ($query) use ($search_moods) {
                $query->whereIn('mood', $search_moods);
            }, '=', count($search_moods));
        }

        // Filter out current user
        $query->where('users.id', '!=', $currentUserId);

        // Filter inactive users
        $query->where('status', 1);

        // Sort by id in descending order
        $query->orderByDesc('id');
        
        // Get the search results
        $users = $query->groupBy('id')->paginate(10);

        return view('search', compact('users', 'search', 'search_genres', 'search_specialties', 'search_moods', 'search_counties'));
    }

    public function searchResource(Request $request) {
        $search = $request->search;

        $query = Resource::query();

        // Select statement with aliases
        $query->select('resources.*');

        $query->where(function ($query) use ($search, $request) {
            if ($search) {
                $query->where('resources.name', 'like', "%$search%")
                    ->orWhere('resources.description', 'like', "%$search%")
                    ->orWhere('resources.type', 'like', "%$search%")
                    ->orWhere('resources.county', 'like', "%$search%")
                    ->orWhere('resources.address', 'like', "%$search%");
            }
        });

        // Filter by specialty
        $search_types = array_filter($request->types ?? []);
        if ($search_types) {
            $query->whereIn('resources.type', $search_types);
        }

        // Filter by county if specified
        $search_counties = array_filter($request->counties ?? []);
        if ($search_counties) {
            $query->whereIn('resources.county', $search_counties);
        }

        // Sort by id in descending order
        $query->orderByDesc('id');

        // Get the search results
        $resources = $query->groupBy('id')->paginate(10);

        $user = Auth::user();

        return view('resources.index', compact('resources', 'search', 'search_types', 'search_counties', 'user'));
    }

    public function searchMusic(Request $request) {
        $search = $request->search ?? '';
        $search_moods = $request->moods ?? [];
        $search_genres = $request->genres ?? [];

        // Query for songs
        $songsQuery = Song::query();
        $songsQuery->where(function ($query) use ($search, $search_moods, $search_genres) {
            if ($search) {
                $query->where('title', 'like', "%$search%")
                      ->orWhereHas('user', function ($query) use ($search) {
                          $query->where('name', 'like', "%$search%");
                      });
            }
            if (!empty($search_moods)) {
                $query->whereHas('moods', function ($query) use ($search_moods) {
                    $query->whereIn('mood', $search_moods);
                });
            }
            if (!empty($search_genres)) {
                $query->whereHas('genres', function ($query) use ($search_genres) {
                    $query->whereIn('genre', $search_genres);
                });
            }
        });

        $songs = $songsQuery->get();

        // Associate genres and moods with each song
        foreach ($songs as $song) {
            $song->genres = $song->genres()->pluck('genre')->toArray();
            $song->moods = $song->moods()->pluck('mood')->toArray();
        }
    
        // Query for users
        $usersQuery = User::query();
        $usersQuery->where(function ($query) use ($search, $search_moods, $search_genres) {
            if ($search) {
                $query->where('name', 'like', "%$search%");
            }
            if (!empty($search_moods)) {
                $query->whereHas('moods', function ($query) use ($search_moods) {
                    $query->whereIn('mood', $search_moods);
                });
            }
            if (!empty($search_genres)) {
                $query->whereHas('genres', function ($query) use ($search_genres) {
                    $query->whereIn('name', $search_genres);
                });
            }
        });

        $users = $usersQuery->get();
    
        return view('music', compact('songs', 'users', 'search', 'search_moods', 'search_genres'));
    }

    public function searchSongs(Request $request) {
        $search = $request->search ?? '';
        $search_moods = $request->moods ?? [];
        $search_genres = $request->genres ?? [];

        // Query for songs
        $songsQuery = Song::query();
        $songsQuery->where(function ($query) use ($search, $search_moods, $search_genres) {
            if ($search) {
                $query->where('title', 'like', "%$search%")
                      ->orWhereHas('user', function ($query) use ($search) {
                          $query->where('name', 'like', "%$search%");
                      });
            }
            if (!empty($search_moods)) {
                $query->whereHas('moods', function ($query) use ($search_moods) {
                    $query->whereIn('mood', $search_moods);
                });
            }
            if (!empty($search_genres)) {
                $query->whereHas('genres', function ($query) use ($search_genres) {
                    $query->whereIn('genre', $search_genres);
                });
            }
        });

        $songs = $songsQuery->paginate(15);

        // Associate genres and moods with each song
        foreach ($songs as $song) {
            $song->genres = $song->genres()->pluck('genre')->toArray();
            $song->moods = $song->moods()->pluck('mood')->toArray();
        }
    
        return view('music.songs', compact('songs', 'search', 'search_moods', 'search_genres'));
    }

    public function searchArtists(Request $request) {
        $search = $request->search ?? '';
        $search_moods = $request->moods ?? [];
        $search_genres = $request->genres ?? [];

        // Query for users
        $usersQuery = User::query();
        $usersQuery->where(function ($query) use ($search, $search_moods, $search_genres) {
            if ($search) {
                $query->where('name', 'like', "%$search%");
            }
            if (!empty($search_moods)) {
                $query->whereHas('moods', function ($query) use ($search_moods) {
                    $query->whereIn('mood', $search_moods);
                });
            }
            if (!empty($search_genres)) {
                $query->whereHas('genres', function ($query) use ($search_genres) {
                    $query->whereIn('name', $search_genres);
                });
            }
        });

        $users = $usersQuery->paginate(15);
    
        return view('music.artists', compact('users', 'search', 'search_moods', 'search_genres'));
    }
}
