<?php

namespace App\Http\Controllers;

use App\Actions\FileHelper;
use App\Models\AdviceImage;
use Illuminate\Http\Request;

class AdviceImageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $images = AdviceImage::all();
        return response()->json($images);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, FileHelper $fileHelper)
    {
        $request->validate([
            'image' => 'required|image',
        ]);

        $filePath = $fileHelper->execute($request->file('image'), 'guardians');

        $image = new AdviceImage();
        $image->image_path = $filePath;
        $image->save();
        
        return response()->json($image);
    }

    /**
     * Display the specified resource.
     */
    public function show(AdviceImage $adviceImage)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AdviceImage $adviceImage)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, AdviceImage $adviceImage)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AdviceImage $adviceImage)
    {
        //
    }
}
