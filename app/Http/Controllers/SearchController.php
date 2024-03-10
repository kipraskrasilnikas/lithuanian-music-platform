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
        return view('search', compact('users'));
    }

    public function search (Request $request) {
        $search = $request->search;
        $currentUserId = auth()->id(); // Get the ID of the current authenticated user

        $users = User::where(function($query) use ($search) {
            $query->where('name', 'like', "%$search%")
            ->orWhere('genre', 'like', "%$search%")
            ->orWhere('specialty', 'like', "%$search%");
            })
            ->orWhereHas('locations', function($query) use($search) {
                $query->where('county', 'like', "%$search%")
                ->orWhere('city', 'like', "%$search%");
            })
            ->get();

        // Filter out the current user from the search results
        $users = $users->reject(function ($user) use ($currentUserId) {
            return $user->id == $currentUserId;
        });
        
        return view('search', compact('users', 'search'));
    }
}
