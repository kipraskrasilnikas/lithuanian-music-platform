<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Resource;

class ResourceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $resources = Resource::all();
        return view ('resources.index')->with('resources', $resources);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('resources.create'); 
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the uploaded file
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Adjust validation rules as needed
        ]);
        
        // Handle the file upload
        $imageName = time() . '.' . $request->image->extension();
        $request->image->move(public_path('images'), $imageName);
        
        // Create resource instance and store it in the database
        $resource = new Resource([
            'name' => $request->name,
            'address' => $request->address,
            'type' => $request->type,
            'description' => $request->description,
            'telephone' => $request->telephone,
            'image' => $imageName,
        ]);

        var_dump($resource); die('akes');
        $resource->save();

        // Redirect back with a success message
        return redirect('resources')->with('success', 'Resursas įkeltas sėkmingai!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
