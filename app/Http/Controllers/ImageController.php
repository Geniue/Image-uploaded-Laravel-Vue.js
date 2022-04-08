<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Image;

class ImageController extends Controller
{
    public function index()
    {
        return view('images.index');
    }


    public function show()
    {
        return Image::latest()->pluck('name')->toArray();
    }


    public function store(Request $request)
    {
        // validate file type
        if (!$request->hasFile('image')) {
            return response()->json(['error' => 'No image present'], 400);
        }

        $request->validate(['image' => 'required|file|image']);
        
        // TODO: Comperssion ALgorithm

        
        // save file
        $path = $request->file('image')->store('public/images');


        if (!$path) {
            return response()->json(['error' => 'The file could not be saved'], 500);
        }

        // store into database
        $uploadedFile = $request->file('image');        
        
        $image = Image::create([
            'name' => $uploadedFile->hashName(),
            'extension' => $uploadedFile->extension(),
            'size' => $uploadedFile->getSize()
        ]);

        return $image->name;
    }
}
