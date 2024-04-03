<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Resource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

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
        $imageName = Str::slug(pathinfo($request->image->getClientOriginalName(), PATHINFO_FILENAME)) . '_' . time() . '.' . $request->image->extension();
        $request->image->move(public_path('images'), $imageName);
        
        // Create resource instance and store it in the database
        $resource = new Resource([
            'name' => $request->name,
            'type' => $request->type,
            'description' => $request->description,
            'image' => $imageName,
            'address' => $request->address,
            'telephone' => $request->telephone,
        ]);

        // Set the current authenticated user's ID as the user_id for the resource
        $resource->user_id = Auth::id();

        $resource->save();

        // Redirect back with a success message
        return redirect('resources')->with('flash_message', 'Resursas įkeltas sėkmingai!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $resource = Resource::find($id);
        return view('resources.show')->with('resources', $resource);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $resource = Resource::find($id);
        return view('resources.edit')->with('resources', $resource);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $resource = Resource::find($id);
        $input = $request->all();
        $resource->update($input);
        return redirect(route('resources.show', $id))->with('flash_message', 'Resursas atnaujintas sėkmingai!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
