<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;


class DBFiles extends Controller
{
    public function index(Request $request)
    {

        // Validate the uploaded image
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Adjust validation rules as needed
        ]);

        // Store the uploaded image in storage/app/public/images directory
         $imagePath = $request->file('image')->store('images', 'public');

        // Optionally, save the image path in your database
        // Example: $image = Image::create(['path' => $imagePath]);

        // Redirect back with success message
        return redirect()->back()->with('success', 'Image uploaded successfully.');
    }
    function loadImages($imageId){
        // return 1;s
         $imagePath = 'public/images/' . $imageId;

        if (Storage::exists($imagePath)) {
            return response()->file(Storage::path($imagePath));
        } else {
            return response()->json(['error' => 'Image not found'], 404);
        }
    }
}
