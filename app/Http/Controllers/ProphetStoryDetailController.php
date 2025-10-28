<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProphetStoryDetail;
use Illuminate\Support\Facades\Storage;

class ProphetStoryDetailController extends Controller
{
    public function index()
    {
        $stories = ProphetStoryDetail::all();
        return response()->json($stories);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'audio' => 'nullable',
            'image' => 'nullable|file|mimes:jpeg,png,jpg',
            'prophet_story_id' => 'required|integer|exists:prophet_stories,id',
        ]);

        $audioPath = $request->file('audio') ? $request->file('audio')->store('prophet_story_details/audio', 'public') : null;
        $imagePath = $request->file('image') ? $request->file('image')->store('prophet_story_details/images', 'public') : null;

        $story = ProphetStoryDetail::create([
            'title' => $request->title,
            'content' => $request->content,
            'audio_path' => $audioPath,
            'image_path' => $imagePath,
            'prophet_story_id' => $request->prophet_story_id,
        ]);

        return response()->json($story, 201);
    }

    public function show($id)
    {
        $story = ProphetStoryDetail::findOrFail($id);
        return response()->json($story);
    }

    public function update(Request $request, $id)
    {
        $story = ProphetStoryDetail::findOrFail($id);
        
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'audio' => 'nullable',
            'image' => 'nullable|file|mimes:jpeg,png,jpg',
        ]);

        if ($request->hasFile('audio')) {
            Storage::disk('public')->delete($story->audio_path);
            $story->audio_path = $request->file('audio')->store('prophet_story_details/audio', 'public');
        }
        if ($request->hasFile('image')) {
            Storage::disk('public')->delete($story->image_path);
            $story->image_path = $request->file('image')->store('prophet_story_details/images', 'public');
        }

        $story->update($request->only(['title', 'content', 'prophet_story_id']));
        return response()->json($story);
    }

    public function destroy($id)
    {
        $story = ProphetStoryDetail::findOrFail($id);
        Storage::disk('public')->delete([$story->audio_path, $story->image_path]);
        $story->delete();

        return response()->json(['message' => 'Deleted successfully']);
    }
}
