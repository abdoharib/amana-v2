<?php

namespace App\Http\Controllers;

use App\Actions\FileHelper;
use App\Http\Requests\StoryRequest;
use App\Http\Resources\StoryResource;
use App\Models\Story;
use Illuminate\Http\Response;

class StoryController extends Controller
{
    public function typeIndex()
    {
        $stories = Story::filter()->get();

        return response()->json($stories);
    }
    public function index()
    {
        $stories = Story::filter()->get();
        return response()->json($stories);
    }


    public function store(StoryRequest $request, FileHelper $fileHelper)
    {
        if ($request->hasFile('image')) {
            $image = $fileHelper->execute($request->file('image'), 'stories');
        }
        if ($request->hasFile('audio')) {
            $filePath = $fileHelper->execute($request->file('audio'), 'stories');
        }
        $content = Story::create(array_merge($request->validated(), [
            'image_path' => $image,
            'audio_path' => $filePath
        ]));
        return new StoryResource($content);
    }

    public function show(Story $story)
    {
        return new StoryResource($story);
    }

    public function update(StoryRequest $request, Story $story, FileHelper $fileHelper)
    {       
        // Set the new file path if an image is uploaded, or retain the old path otherwise
        $filePath = $request->hasFile('image')
            ? $fileHelper->execute($request->file('image'), 'stories')
            : $story->image_path;


        $audioPath = $request->hasFile('audio')
            ? $fileHelper->execute($request->file('audio'), 'stories')
            : $story->audio_path;

        // dd($filePath);
        // Update the Guardian model with both the new request data and the processed image path
        $story->update(array_merge($request->validated(), [
            'image_path' => $filePath,
            'audio_path' => $audioPath
        ]));

        return new StoryResource($story);
    }

    public function destroy(Story $story)
    {

        $story->delete();
        return response()->json(['message' => 'Educational content deleted successfully.'], Response::HTTP_NO_CONTENT);
    }
}
