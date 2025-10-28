<?php

namespace App\Http\Controllers;

use App\Models\PrayerContent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PrayerContentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $prayerContents = PrayerContent::filter()->get();
        return response()->json($prayerContents);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'content' => 'required|string',
            'prayer_id' => 'required|exists:prayers,id',
            'audio' => 'nullable', // 20MB max
        ]);

        $audioPath = null;
        if ($request->hasFile('audio')) {
            $audioPath = $request->file('audio')->store('prayer_audio', 'public');
        }

        $prayerContent = PrayerContent::create([
            'content' => $validated['content'],
            'prayer_id' => $validated['prayer_id'],
            'audio_file' => $audioPath,
        ]);

        return response()->json(['message' => 'Prayer content created successfully', 'data' => $prayerContent], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(PrayerContent $prayerContent)
    {
        return response()->json($prayerContent);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PrayerContent $prayerContent)
    {
        $validated = $request->validate([
            'content' => 'required|string',
            'prayer_id' => 'required|exists:prayers,id',
            'audio' => 'nullable',
        ]);

        if ($request->hasFile('audio')) {
            // Delete old file if exists
            if ($prayerContent->audio_file) {
                $oldFilePath = str_replace('/storage/', '', $prayerContent->audio_file);
                Storage::disk('public')->delete($oldFilePath);
            }

            // Store new file
            $audioPath = $request->file('audio')->store('prayer_audio', 'public');
            $prayerContent->audio_file = $audioPath;
        }

        $prayerContent->update([
            'content' => $validated['content'],
            'prayer_id' => $validated['prayer_id'],
        ]);

        return response()->json(['message' => 'Prayer content updated successfully', 'data' => $prayerContent]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PrayerContent $prayerContent)
    {
        if ($prayerContent->audio_file) {
            $filePath = str_replace('/storage/', '', $prayerContent->audio_file);
            Storage::disk('public')->delete($filePath);
        }

        $prayerContent->delete();

        return response()->json(['message' => 'Prayer content deleted successfully']);
    }
}
