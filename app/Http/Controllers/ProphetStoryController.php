<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProphetStory;
use App\Http\Requests\UpdateProphetStory;
use App\Http\Resources\ProphetStoryCollection;
use App\Http\Resources\ProphetStoryResource;
use App\Models\ProphetStory;
use App\Models\ProphetStoryDetail;
use Illuminate\Http\Request;

class ProphetStoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return new ProphetStoryCollection(ProphetStory::all());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    public function store(StoreProphetStory $request)
    {
        $validated = $request->validated();

        // Handle story image and audio upload
        if ($request->hasFile('image')) {
            $validated['image_path'] = $request->file('image')->store('prophet_stories/images', 'public');
        }

        // Create the story
        $story = ProphetStory::create($validated);

        // Add details
        foreach ($validated['details'] as $index => $detail) {
            if (isset($detail['image']) && $request->hasFile("details.$index.image")) {
                $detail['image_path'] = $request->file("details.$index.image")->store('prophet_story_details/images', 'public');
            }

            if (isset($detail['audio']) && $request->hasFile("details.$index.audio")) {

                $detail['audio_path'] = $request->file("details.$index.audio")->store('prophet_story_details/audio', 'public');
             
            }

            $story->details()->create($detail);
        }

        return response()->json($story->load('details'), 201);
    }


    /**
     * Display the specified resource.
     */
    public function show(ProphetStory $prophetStory)
    {
        return new ProphetStoryResource($prophetStory);
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ProphetStory $prophetStory)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProphetStory $request, ProphetStory $prophetStory)
    {
        // Validate the request
        $validated = $request->validated();

        // Update the main story image if provided
        if ($request->hasFile('image')) {
            $validated['image_path'] = $request->file('image')->store('prophet_stories/images', 'public');
        }

       
        // Update the main story fields
        $prophetStory->update($validated);


        if(isset($validated['deletedIds'])){
            ProphetStoryDetail::destroy($validated['deletedIds']);
        }
        // Process the story details
        if (isset($validated['details'])) {
            foreach ($validated['details'] as $index => $detail) {
                if ($detail['id']) {
                    // Update an existing detail
                    $storyDetail = ProphetStoryDetail::findOrFail($detail['id']);

                    // Update the image if provided
                    if (isset($detail['image']) && $request->hasFile("details.$index.image")) {
                        $detail['image_path'] = $request->file("details.$index.image")->store('prophet_story_details/images', 'public');
                    }

                    if (isset($detail['audio']) && $request->hasFile("details.$index.audio")) {
                        $detail['audio_file'] = $request->file("details.$index.audio")->store('prophet_story_details/audio', 'public');
                    }
                    // Update the existing detail
                    $storyDetail->update($detail);
                } else {
                    // Create a new detail if no ID is provided
                    if (isset($detail['image']) && $request->hasFile("details.$index.image")) {
                        $detail['image_path'] = $request->file("details.$index.image")->store('prophet_story_details/images', 'public');
                    }
                    if (isset($detail['audio']) && $request->hasFile("details.$index.audio")) {
                        $detail['audio_path'] = $request->file("details.$index.audio")->store('prophet_story_details/audio', 'public');
                    }

                    // Create the new detail
                    $prophetStory->details()->create($detail);
                }
            }
        }

        // Return the updated story with its details
        return response()->json($prophetStory->load('details'));
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProphetStory $prophetStory)
    {
        //
    }
}
