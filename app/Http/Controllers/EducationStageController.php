<?php

namespace App\Http\Controllers;

use App\Actions\FileHelper;
use App\Http\Requests\EducationStageRequest;
use App\Models\EducationStage;
use Illuminate\Http\Request;

class EducationStageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
      
        return response()->json(['success' => true,'data' => EducationStage::all()]);
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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(EducationStage $educationStage)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(EducationStage $educationStage)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(EducationStageRequest $request, EducationStage $educationStage,FileHelper $fileHelper)
    {
        // Delete the old image if a new one is being uploaded
        if($request->hasFile('image')) $fileHelper->deleteFile($educationStage->image_path);

        // Set the new file path if an image is uploaded, or retain the old path otherwise
        $filePath = $request->hasFile('image')
            ? $fileHelper->execute($request->file('image'), 'stages')
            : $educationStage->image_path;

        // Update the Guardian model with both the new request data and the processed image path
        $educationStage->update(array_merge($request->all(), ['image_path' => $filePath]));

        return response()->json(['success' => true,'data' => $educationStage]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(EducationStage $educationStage)
    {
        //
    }
}
