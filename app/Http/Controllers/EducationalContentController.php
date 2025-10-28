<?php

namespace App\Http\Controllers;

use App\Actions\FileHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\EducationalContentRequest;
use App\Http\Resources\EducationalContentResource;
use App\Models\EducationalContent;
use Illuminate\Http\Response;

class EducationalContentController extends Controller
{
    public function typeIndex()
    {
        $contents = EducationalContent::with('educationStage')->filter()->get();
        $grouped = $contents->groupBy('type')->map(function ($items, $type) {
            return [
                'type' => $type,
                'content' => $items,
            ];
        });
    
        return response()->json($grouped->values());
    
    }
    public function index()
    {
        $contents = EducationalContent::with('educationStage')->filter()->get();
        
    
        return response()->json($contents);
    
    }


    public function store(EducationalContentRequest $request, FileHelper $fileHelper)
    {
        if ($request->hasFile('image')) {
            $filePath = $fileHelper->execute($request->file('image'), 'contents');
            $request->merge(['image_path' => $filePath]);
        }
        $content = EducationalContent::create($request->validated());
        return new EducationalContentResource($content);
    }

    public function show(EducationalContent $educationalContent)
    {
        return new EducationalContentResource($educationalContent);
    }

    public function update(EducationalContentRequest $request, EducationalContent $educationalContent, FileHelper $fileHelper)
    {
        // Delete the old image if a new one is being uploaded
        $request->hasFile('image') && $fileHelper->deleteFile($educationalContent->image_path);

        // Set the new file path if an image is uploaded, or retain the old path otherwise
        $filePath = $request->hasFile('image')
            ? $fileHelper->execute($request->file('image'), 'contents')
            : $educationalContent->image_path;

        // dd($filePath);
        // Update the Guardian model with both the new request data and the processed image path
        $educationalContent->update(array_merge($request->validated(), ['image_path' => $filePath]));


        return new EducationalContentResource($educationalContent);
    }

    public function destroy(EducationalContent $educationalContent)
    {
        $educationalContent->delete();
        return response()->json(['message' => 'Educational content deleted successfully.'], Response::HTTP_NO_CONTENT);
    }
}
